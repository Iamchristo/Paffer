<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    private function createEvent(User $organizer, array $overrides = []): Event
    {
        $event = $organizer->organizedEvents()->create([
            'title' => 'Founders Meetup',
            'slug' => 'founders-meetup-'.Str::random(6),
            'description' => 'Monthly meetup for founders.',
            'starts_at' => now()->addWeek(),
            ...$overrides,
        ]);

        $event->attendees()->attach($organizer);

        return $event;
    }

    public function test_guests_can_browse_events(): void
    {
        $organizer = User::factory()->create();
        $event = $this->createEvent($organizer);

        $this->get('/events')->assertOk();
        $this->get(route('events.show', $event))->assertOk();
    }

    public function test_user_can_create_an_event_and_is_added_as_an_attendee(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/events', [
                'title' => 'Startup Pitch Night',
                'description' => 'Pitch your startup to investors.',
                'location' => 'Downtown Hub',
                'starts_at' => now()->addWeek()->format('Y-m-d H:i:s'),
            ]);

        $event = Event::first();

        $response->assertRedirect(route('events.show', $event));
        $this->assertSame($user->id, $event->organizer_id);
        $this->assertTrue($event->hasAttendee($user));
    }

    public function test_user_can_rsvp_and_cancel_rsvp(): void
    {
        $organizer = User::factory()->create();
        $attendee = User::factory()->create();
        $event = $this->createEvent($organizer);

        $this->actingAs($attendee)->post(route('events.rsvp.store', $event))
            ->assertRedirect(route('events.show', $event));

        $this->assertTrue($event->hasAttendee($attendee));

        $this->actingAs($attendee)->delete(route('events.rsvp.destroy', $event))
            ->assertRedirect(route('events.show', $event));

        $this->assertFalse($event->hasAttendee($attendee));
    }

    public function test_rsvp_is_rejected_once_event_is_at_capacity(): void
    {
        $organizer = User::factory()->create();
        $event = $this->createEvent($organizer, ['capacity' => 1]);

        $latecomer = User::factory()->create();

        $this->actingAs($latecomer)->post(route('events.rsvp.store', $event));

        $this->assertFalse($event->hasAttendee($latecomer));
    }

    public function test_organizer_cannot_cancel_their_own_rsvp(): void
    {
        $organizer = User::factory()->create();
        $event = $this->createEvent($organizer);

        $this->actingAs($organizer)->delete(route('events.rsvp.destroy', $event))
            ->assertForbidden();
    }
}
