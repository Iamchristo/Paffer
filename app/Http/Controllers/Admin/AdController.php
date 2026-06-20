<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->trim()->toString();

        $ads = Ad::query()
            ->with('advertiser')
            ->when($search, fn ($query, $search) => $query->where(
                fn ($query) => $query->where('title', 'like', "%{$search}%")
                    ->orWhereHas('advertiser', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ))
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.ads.index', [
            'ads' => $ads,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function approve(Ad $ad): RedirectResponse
    {
        $ad->update(['status' => 'approved']);

        return back()->with('status', 'ad-approved');
    }

    public function reject(Ad $ad): RedirectResponse
    {
        $ad->update(['status' => 'rejected']);

        return back()->with('status', 'ad-rejected');
    }

    public function destroy(Ad $ad): RedirectResponse
    {
        $ad->delete();

        return back()->with('status', 'ad-deleted');
    }
}
