<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Setting;
use App\Models\User;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NetworkController extends Controller
{
    public function index(Request $request, RecommendationService $recommendations): View
    {
        $user = $request->user();

        $followingIds = $user->following()->pluck('users.id')->push($user->id);

        $posts = \App\Models\Post::with(['user.profile', 'likes', 'comments.user'])
            ->whereIn('user_id', $followingIds)
            ->latest()
            ->paginate(10);

        $suggestions = Setting::getBool('recommend_on_feed', true)
            ? $recommendations->people($user)
            : User::where('id', '!=', $user->id)
                ->whereNotIn('id', $user->following()->pluck('users.id'))
                ->inRandomOrder()
                ->limit(5)
                ->get();

        $ads = Ad::where('status', 'approved')->inRandomOrder()->limit(3)->get();

        return view('network.feed', [
            'posts' => $posts,
            'suggestions' => $suggestions,
            'ads' => $ads,
        ]);
    }
}
