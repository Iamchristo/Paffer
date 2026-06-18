<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        abort_unless($product->isActive() && $product->store->isApproved(), 404);

        $product->load('store');

        return view('marketplace.product', [
            'product' => $product,
        ]);
    }
}
