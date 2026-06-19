<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdController extends Controller
{
    public function index(Request $request): View
    {
        $ads = $request->user()->ads()->latest()->paginate(10);

        return view('ads.index', [
            'ads' => $ads,
        ]);
    }

    public function create(): View
    {
        return view('ads.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:2000'],
            'target_url' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $imagePath = $request->file('image')?->store('ads', 'public');

        $request->user()->ads()->create([
            'title' => $validated['title'],
            'body' => $validated['body'] ?? null,
            'target_url' => $validated['target_url'] ?? null,
            'image_path' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('ads.index')->with('status', 'ad-submitted');
    }

    public function destroy(Request $request, Ad $ad): RedirectResponse
    {
        abort_unless($ad->advertiser_id === $request->user()->id, 403);

        $ad->delete();

        return back()->with('status', 'ad-deleted');
    }
}
