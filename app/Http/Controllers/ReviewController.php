<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id'   => 'required|exists:orders,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        // Pastikan order milik user ini dan statusnya delivered
        $order = Order::where('id', $request->order_id)
            ->where('user_id', auth()->id())
            ->where('status', 'delivered')
            ->firstOrFail();

        // Pastikan produk ada di order ini
        $hasProduct = $order->items()->where('product_id', $request->product_id)->exists();
        abort_unless($hasProduct, 403, 'Produk tidak ditemukan di pesanan ini.');

        ProductReview::updateOrCreate(
            [
                'user_id'    => auth()->id(),
                'product_id' => $request->product_id,
                'order_id'   => $request->order_id,
            ],
            [
                'rating'  => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Ulasan berhasil disimpan. Terima kasih!');
    }

    public function destroy(ProductReview $review)
    {
        abort_unless($review->user_id === auth()->id(), 403);

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
