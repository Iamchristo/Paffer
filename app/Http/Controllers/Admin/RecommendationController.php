<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecommendationController extends Controller
{
    public function edit(): View
    {
        return view('admin.recommendations', [
            'values' => [
                'recommend_on_feed' => Setting::getBool('recommend_on_feed', true),
                'recommend_on_marketplace' => Setting::getBool('recommend_on_marketplace', true),
                'recommend_on_learn' => Setting::getBool('recommend_on_learn', true),
            ],
            'stats' => [
                'users_with_industry' => User::whereHas('profile', fn ($q) => $q->whereNotNull('industry'))->count(),
                'active_products' => Product::where('status', 'active')->count(),
                'approved_courses' => Course::where('status', 'approved')->count(),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'recommend_on_feed' => ['nullable', 'boolean'],
            'recommend_on_marketplace' => ['nullable', 'boolean'],
            'recommend_on_learn' => ['nullable', 'boolean'],
        ]);

        Setting::set('recommend_on_feed', $request->boolean('recommend_on_feed'));
        Setting::set('recommend_on_marketplace', $request->boolean('recommend_on_marketplace'));
        Setting::set('recommend_on_learn', $request->boolean('recommend_on_learn'));

        return back()->with('status', 'recommendations-updated');
    }
}
