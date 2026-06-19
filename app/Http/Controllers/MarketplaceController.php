<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Setting;
use App\Models\Store;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(Request $request, RecommendationService $recommendations): View
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

        $recommendedProducts = $request->user() && Setting::getBool('recommend_on_marketplace', true)
            ? $recommendations->products($request->user())
            : collect();

        return view('marketplace.index', [
            'stores' => $stores,
            'search' => $search,
            'ads' => $ads,
            'recommendedProducts' => $recommendedProducts,
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
