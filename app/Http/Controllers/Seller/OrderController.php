<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()->store->orders()->with(['items.product', 'buyer'])->latest()->paginate(15);

        return view('seller.orders.index', [
            'orders' => $orders,
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->store_id === $request->user()->store->id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
        ]);

        $order->update($validated);

        return back()->with('status', 'order-updated');
    }
}
