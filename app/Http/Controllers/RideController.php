<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RideController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();

        $rides = Ride::query()
            ->with(['driver', 'event'])
            ->when($search, function ($query, $search) {
                $query->where('origin', 'like', "%{$search}%")
                    ->orWhere('destination', 'like', "%{$search}%");
            })
            ->orderBy('departure_at')
            ->paginate(12)
            ->withQueryString();

        return view('rides.index', [
            'rides' => $rides,
            'search' => $search,
        ]);
    }

    public function create(Request $request): View
    {
        return view('rides.create', [
            'eventId' => $request->integer('event_id') ?: null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'departure_at' => ['required', 'date'],
            'seats_total' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'event_id' => ['nullable', 'integer', 'exists:events,id'],
        ]);

        $ride = $request->user()->ridesOffered()->create($validated);

        return redirect()->route('rides.show', $ride)->with('status', 'ride-created');
    }

    public function show(Request $request, Ride $ride): View
    {
        $ride->load(['driver', 'event', 'bookings.passenger']);

        return view('rides.show', [
            'ride' => $ride,
            'hasBooking' => $request->user() ? $ride->hasBooking($request->user()) : false,
        ]);
    }
}
