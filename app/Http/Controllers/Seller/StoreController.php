<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function dashboard(Request $request): View
    {
        $store = $request->user()->store;

        $store?->loadCount('products');
        $store?->load(['orders' => fn ($query) => $query->latest()->limit(5)]);

        return view('seller.dashboard', [
            'store' => $store,
        ]);
    }

    public function edit(Request $request): View
    {
        return view('seller.store-edit', [
            'store' => $request->user()->store,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'banner' => ['nullable', 'image', 'max:4096'],
        ]);

        $store = $request->user()->store;

        $store->fill([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        if ($request->hasFile('logo')) {
            $store->logo_path = $request->file('logo')->store('stores', 'public');
        }

        if ($request->hasFile('banner')) {
            $store->banner_path = $request->file('banner')->store('stores', 'public');
        }

        $store->save();

        return redirect()->route('seller.store.edit')->with('status', 'store-updated');
    }
}
