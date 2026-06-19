<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();

        $events = Event::query()
            ->withCount('attendees')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            })
            ->orderBy('starts_at')
            ->paginate(12)
            ->withQueryString();

        return view('events.index', [
            'events' => $events,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $event = $request->user()->organizedEvents()->create([
            ...$validated,
            'slug' => Str::slug($validated['title']).'-'.Str::random(6),
        ]);

        $event->attendees()->attach($request->user());

        return redirect()->route('events.show', $event)->with('status', 'event-created');
    }

    public function show(Request $request, Event $event): View
    {
        $event->load(['organizer', 'rides.driver']);

        return view('events.show', [
            'event' => $event,
            'attendeesCount' => $event->attendees()->count(),
            'isAttending' => $request->user()?->eventsAttending()->where('events.id', $event->id)->exists() ?? false,
        ]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'location' => ['nullable', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'capacity' => ['nullable', 'integer', 'min:1'],
        ]);
    }
}
