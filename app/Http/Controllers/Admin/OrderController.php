<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->trim()->toString();

        $orders = Order::query()
            ->with(['buyer', 'store'])
            ->when($search, fn ($query, $search) => $query->where(
                fn ($query) => $query->whereHas('buyer', fn ($query) => $query->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('store', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ))
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['buyer', 'store', 'items.product', 'shipment']);

        return view('admin.orders.show', [
            'order' => $order,
        ]);
    }
}
