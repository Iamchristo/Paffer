<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeopleController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();

        $people = User::query()
            ->with('profile')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($profileQuery) use ($search) {
                        $profileQuery->where('industry', 'like', "%{$search}%")
                            ->orWhere('business_name', 'like', "%{$search}%")
                            ->orWhere('headline', 'like', "%{$search}%");
                    });
            })
            ->paginate(12)
            ->withQueryString();

        return view('network.people', [
            'people' => $people,
            'search' => $search,
        ]);
    }

    public function show(Request $request, User $user): View
    {
        $user->load(['profile.skills', 'posts' => fn ($query) => $query->latest()]);

        return view('network.profile-show', [
            'profileUser' => $user,
            'isFollowing' => $request->user()?->isFollowing($user) ?? false,
            'followersCount' => $user->followers()->count(),
            'followingCount' => $user->following()->count(),
        ]);
    }
}
