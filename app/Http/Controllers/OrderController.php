<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order, MidtransService $midtrans)
{
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }

    $order->load(['items.product', 'user']);

    $snapToken = null;
    if ($order->status === 'pending') {
        $snapToken = $midtrans->createSnapToken($order);
    }

    return view('orders.show', compact('order', 'snapToken'));
}
    

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.success', compact('order'));
    }

    public function pending(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.pending', compact('order'));
    }
}