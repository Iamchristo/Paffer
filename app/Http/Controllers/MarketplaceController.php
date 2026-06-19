<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();

        $stores = Store::query()
            ->where('status', 'approved')
            ->when($search, fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->withCount('products')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $ads = Ad::where('status', 'approved')->inRandomOrder()->limit(3)->get();

        return view('marketplace.index', [
            'stores' => $stores,
            'search' => $search,
            'ads' => $ads,
        ]);
    }

    public function show(Store $store): View
    {
        abort_unless($store->status === 'approved', 404);

        $store->load(['products' => fn ($query) => $query->where('status', 'active'), 'reviews.user']);

        return view('marketplace.store', [
            'store' => $store,
        ]);
    }
}
