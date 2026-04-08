<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductReview;
use App\Services\TrackingService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($invoice)
    {
        $order = Order::with('items.product')
            ->where('invoice_number', $invoice)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Kumpulkan review yang sudah dibuat user untuk order ini (keyed by product_id)
        $existingReviews = ProductReview::where('user_id', auth()->id())
            ->where('order_id', $order->id)
            ->get()
            ->keyBy('product_id');

        return view('orders.show', compact('order', 'existingReviews'));
    }

    public function destroy($invoice)
    {
        $order = Order::where('invoice_number', $invoice)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        // Stock tidak dikurangi saat order dibuat, sehingga tidak perlu di-restore saat cancel.
        // Stok hanya berkurang setelah pembayaran confirmed via Midtrans webhook.
        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function track($invoice)
    {
        $order = Order::where('invoice_number', $invoice)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!$order->tracking_number || !$order->courier) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Nomor resi belum tersedia.',
            ]);
        }

        $tracking = new TrackingService();
        $result   = $tracking->track($order->courier, $order->tracking_number);

        return response()->json($result);
    }
}
