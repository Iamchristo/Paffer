<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()->orders()->with(['store', 'items.product'])->latest()->paginate(10);

        return view('marketplace.orders', [
            'orders' => $orders,
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->buyer_id === $request->user()->id, 403);

        $order->load(['items.product', 'store', 'shipment']);

        return view('marketplace.order-show', [
            'order' => $order,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        abort_if(empty($cart), 400, 'Your cart is empty.');

        $payWithWallet = $request->boolean('pay_with_wallet');
        $products = Product::with('store')->whereIn('id', array_keys($cart))->get()->keyBy('id');

        if ($payWithWallet) {
            $estimatedTotal = $products->sum(fn ($product) => min($product->stock, $cart[$product->id]) * $product->price_cents);

            if ($request->user()->wallet->balance_cents < $estimatedTotal) {
                return back()->with('error', 'wallet-insufficient-funds');
            }
        }

        DB::transaction(function () use ($cart, $products, $request, $payWithWallet) {
            foreach ($products->groupBy('store_id') as $storeId => $storeProducts) {
                $totalCents = 0;

                $order = Order::create([
                    'buyer_id' => $request->user()->id,
                    'store_id' => $storeId,
                    'status' => 'pending',
                    'total_cents' => 0,
                ]);

                foreach ($storeProducts as $product) {
                    $quantity = min($product->stock, $cart[$product->id]);

                    if ($quantity <= 0) {
                        continue;
                    }

                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price_cents' => $product->price_cents,
                    ]);

                    $product->decrement('stock', $quantity);
                    $totalCents += $quantity * $product->price_cents;
                }

                $order->update(['total_cents' => $totalCents]);

                if ($payWithWallet && $totalCents > 0) {
                    $wallet = $request->user()->wallet;
                    $wallet->debit($totalCents);
                    $wallet->transactions()->create([
                        'order_id' => $order->id,
                        'type' => 'payment',
                        'amount_cents' => -$totalCents,
                        'status' => 'completed',
                        'description' => "Payment for order #{$order->id}",
                    ]);
                    $order->update(['status' => 'processing']);
                }
            }
        });

        $request->session()->forget('cart');

        return redirect()->route('orders.index')->with('status', 'order-placed');
    }
}
