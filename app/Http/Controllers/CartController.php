<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);

        $products = Product::with('store')->whereIn('id', array_keys($cart))->get();

        $items = $products->map(fn (Product $product) => [
            'product' => $product,
            'quantity' => $cart[$product->id],
            'subtotal_cents' => $product->price_cents * $cart[$product->id],
        ]);

        return view('marketplace.cart', [
            'items' => $items,
            'totalCents' => $items->sum('subtotal_cents'),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->isActive(), 404);

        $quantity = max(1, (int) $request->input('quantity', 1));

        $cart = $request->session()->get('cart', []);
        $cart[$product->id] = min($product->stock, ($cart[$product->id] ?? 0) + $quantity);
        $request->session()->put('cart', $cart);

        return back()->with('status', 'added-to-cart');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $quantity = max(1, (int) $request->input('quantity', 1));

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id] = min($product->stock, $quantity);
            $request->session()->put('cart', $cart);
        }

        return back();
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);

        return back();
    }
}
