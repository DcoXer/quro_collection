<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Order;

class NotificationController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $perPage = in_array((int) $request->input('per_page'), [10, 25, 50]) ? (int) $request->input('per_page') : 20;

        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $pendingOrders = Order::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Tandai semua sebagai dibaca
        Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications', 'pendingOrders', 'perPage'));
    }

    public function markRead(Notification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $notification->update(['read_at' => now()]);

        return redirect($notification->url ?? route('orders.index'));
    }
}
