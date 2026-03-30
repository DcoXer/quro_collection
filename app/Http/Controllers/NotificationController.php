<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Order;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        $pendingOrders = Order::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Tandai semua sebagai dibaca
        Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications', 'pendingOrders'));
    }

    public function markRead(Notification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $notification->update(['read_at' => now()]);

        return redirect($notification->url ?? route('orders.index'));
    }
}
