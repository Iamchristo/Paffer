<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'profile' => $request->user()->profile()->with('skills')->first(),
        ]);
    }

    public function updateInfo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'headline' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'industry' => ['nullable', 'string', 'max:255'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'cover' => ['nullable', 'image', 'max:4096'],
            'skills' => ['nullable', 'string', 'max:500'],
        ]);

        $profile = $request->user()->profile;

        $profile->fill([
            'headline' => $validated['headline'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'industry' => $validated['industry'] ?? null,
            'business_name' => $validated['business_name'] ?? null,
            'website' => $validated['website'] ?? null,
            'location' => $validated['location'] ?? null,
        ]);

        if ($request->hasFile('avatar')) {
            $profile->avatar_path = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('cover')) {
            $profile->cover_path = $request->file('cover')->store('covers', 'public');
        }

        $profile->save();

        $skillNames = collect(explode(',', $validated['skills'] ?? ''))
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique();

        $skillIds = $skillNames->map(
            fn ($name) => Skill::firstOrCreate(['name' => $name])->id
        );

        $profile->skills()->sync($skillIds);

        return Redirect::route('profile.edit')->with('status', 'profile-info-updated');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
