<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RideBookingController extends Controller
{
    public function store(Request $request, Ride $ride): RedirectResponse
    {
        $user = $request->user();

        abort_if($ride->driver_id === $user->id, 403);

        if ($ride->hasBooking($user)) {
            return back()->with('status', 'ride-already-booked');
        }

        $validated = $request->validate([
            'seats_booked' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['seats_booked'] > $ride->seatsAvailable()) {
            return back()->with('status', 'ride-not-enough-seats');
        }

        $user->rideBookings()->create([
            'ride_id' => $ride->id,
            'seats_booked' => $validated['seats_booked'],
        ]);

        return redirect()->route('rides.show', $ride)->with('status', 'ride-booked');
    }

    public function destroy(Request $request, Ride $ride): RedirectResponse
    {
        $request->user()->rideBookings()->where('ride_id', $ride->id)->delete();

        return redirect()->route('rides.show', $ride)->with('status', 'ride-booking-cancelled');
    }
}
