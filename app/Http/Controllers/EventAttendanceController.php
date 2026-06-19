<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EventAttendanceController extends Controller
{
    public function store(Request $request, Event $event): RedirectResponse
    {
        $user = $request->user();

        if (! $event->hasAttendee($user) && $event->isFull()) {
            return back()->with('status', 'event-full');
        }

        $event->attendees()->syncWithoutDetaching([$user->id]);

        return redirect()->route('events.show', $event)->with('status', 'event-rsvp');
    }

    public function destroy(Request $request, Event $event): RedirectResponse
    {
        abort_if($event->organizer_id === $request->user()->id, 403);

        $event->attendees()->detach($request->user());

        return redirect()->route('events.show', $event)->with('status', 'event-rsvp-cancelled');
    }
}
