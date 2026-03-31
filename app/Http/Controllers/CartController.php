<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
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
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = max(1, (int) $request->quantity);
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
