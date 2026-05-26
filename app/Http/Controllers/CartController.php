<?php

namespace App\Http\Controllers;

use App\Models\FlashSaleItem;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index()
    {
        $sessionCart = session()->get('cart', []);

        if (empty($sessionCart)) {
            $cart  = [];
            $total = 0;
            return view('cart.index', compact('cart', 'total'));
        }

        try {
            ['items' => $resolved, 'total' => $total] = $this->cartService->resolveItems($sessionCart);
        } catch (\Exception $e) {
            $cart  = array_values($sessionCart);
            $total = collect($sessionCart)->sum(fn($item) => $item['price'] * $item['quantity']);
            return view('cart.index', compact('cart', 'total'));
        }

        $flashItems = FlashSaleItem::whereHas('flashSale', fn($q) => $q->active())
            ->with('flashSale')
            ->get()
            ->keyBy('product_id');

        // Rebuild cart keyed by product_id_size so route actions still work
        $cart = [];
        foreach ($resolved as $item) {
            $key       = $item['product_id'] . '_' . $item['size'];
            $flash     = $flashItems->get($item['product_id']);
            $basePrice = $item['product']->variants->where('size', $item['size'])->first()?->effective_price
                      ?? $item['product']->price;

            $cart[$key] = [
                'product_id'     => $item['product_id'],
                'name'           => $item['product']->name,
                'image'          => $item['product']->image,
                'size'           => $item['size'],
                'quantity'       => $item['quantity'],
                'price'          => $item['price'],
                'original_price' => $flash ? $basePrice : null,
                'flash_sale'     => $flash ? true : false,
            ];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'size'       => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        $variant = $product->variants()->where('size', $request->size)->first();
        if (! $variant) {
            return back()->with('error', 'Size tidak tersedia.');
        }

        $price = $variant->effective_price;
        $stock = $variant->stock;

        if ($request->quantity > $stock) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart = session()->get('cart', []);
        $key  = $product->id . '_' . $request->size;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'size'       => $request->size,
                'price'      => $price,
                'image'      => $product->image,
                'quantity'   => $request->quantity,
            ];
        }

        session()->put('cart', $cart);

        Notification::send(
            auth()->id(),
            'cart',
            'Produk Ditambahkan ke Keranjang',
            $product->name . ' berhasil ditambahkan ke keranjang belanjamu.',
            route('cart.index')
        );

        return response()->json(['success' => true, 'cartCount' => collect(session('cart'))->sum('quantity')]);
    }

    public function update(Request $request, $productId)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:100']);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $item = $cart[$productId];

            // Validasi stock dari DB — jangan percaya quantity dari client
            $product = Product::with('variants')->find($item['product_id'] ?? explode('_', $productId)[0]);
            if (!$product || !$product->is_active) {
                return response()->json(['success' => false, 'message' => 'Produk tidak tersedia.'], 422);
            }
            $variant = $product->variants->where('size', $item['size'] ?? '')->first();
            $stock   = $variant ? $variant->stock : $product->stock;
            $qty     = min((int) $request->quantity, $stock);

            $cart[$productId]['quantity'] = max(1, $qty);
            session()->put('cart', $cart);
        }

        $cart  = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return response()->json([
            'success'   => true,
            'total'     => $total,
            'formatted' => 'Rp ' . number_format($total, 0, ',', '.'),
            'cartCount' => collect($cart)->sum('quantity'),
        ]);
    }

    public function remove($productId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function changeSize(Request $request, $key)
    {
        $request->validate(['size' => 'required|string']);

        $cart = session()->get('cart', []);

        if (!isset($cart[$key])) {
            return redirect()->route('cart.index');
        }

        $item      = $cart[$key];
        $productId = $item['product_id'];
        $newSize   = $request->size;
        $newKey    = $productId . '_' . $newSize;

        // Cek apakah size baru valid dan ada stoknya
        $product = Product::find($productId);
        if (!$product) return redirect()->route('cart.index');

        $variant = $product->variants()->where('size', $newSize)->first();
        if (! $variant) return redirect()->route('cart.index');
        $price = $variant->effective_price;

        // Hapus item lama
        unset($cart[$key]);

        // Kalau key baru udah ada, tambah quantity
        if (isset($cart[$newKey])) {
            $cart[$newKey]['quantity'] += $item['quantity'];
        } else {
            $cart[$newKey] = array_merge($item, [
                'size'  => $newSize,
                'price' => $price,
            ]);
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Size berhasil diubah.');
    }
}
