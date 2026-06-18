<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = $request->user()->store->products()->latest()->paginate(15);

        return view('seller.products.index', [
            'products' => $products,
        ]);
    }

    public function create(): View
    {
        return view('seller.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $store = $request->user()->store;

        $store->products()->create([
            ...$validated,
            'slug' => Str::slug($validated['name']).'-'.Str::random(6),
            'image_path' => $request->file('image')?->store('products', 'public'),
        ]);

        return redirect()->route('seller.products.index')->with('status', 'product-created');
    }

    public function edit(Request $request, Product $product): View
    {
        $this->authorizeOwner($request, $product);

        return view('seller.products.edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeOwner($request, $product);

        $validated = $this->validated($request);

        $product->fill($validated);

        if ($request->hasFile('image')) {
            $product->image_path = $request->file('image')->store('products', 'public');
        }

        $product->save();

        return redirect()->route('seller.products.index')->with('status', 'product-updated');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeOwner($request, $product);

        $product->delete();

        return redirect()->route('seller.products.index')->with('status', 'product-deleted');
    }

    private function authorizeOwner(Request $request, Product $product): void
    {
        abort_unless($product->store_id === $request->user()->store->id, 403);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price_cents' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);
    }
}
