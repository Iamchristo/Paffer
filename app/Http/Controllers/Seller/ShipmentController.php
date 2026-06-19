<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function update(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->store_id === $request->user()->store->id, 403);

        $validated = $request->validate([
            'carrier' => ['nullable', 'string', 'max:255'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:pending,preparing,shipped,in_transit,delivered'],
        ]);

        $shipment = $order->shipment ?? $order->shipment()->make();

        if ($validated['status'] === 'shipped' && $shipment->shipped_at === null) {
            $validated['shipped_at'] = now();
        }

        if ($validated['status'] === 'delivered' && $shipment->delivered_at === null) {
            $validated['delivered_at'] = now();
        }

        $order->shipment()->updateOrCreate([], $validated);

        return back()->with('status', 'shipment-updated');
    }
}
