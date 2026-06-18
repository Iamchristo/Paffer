<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->is_seller) {
            return redirect()->route('seller.dashboard');
        }

        return view('seller.apply');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_if($request->user()->is_seller, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $slug = Str::slug($validated['name']).'-'.Str::random(6);

        $request->user()->store()->create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        $request->user()->update([
            'is_seller' => true,
            'seller_status' => 'pending',
        ]);

        return redirect()->route('seller.dashboard')->with('status', 'application-submitted');
    }
}
