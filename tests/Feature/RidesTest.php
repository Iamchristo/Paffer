<?php

namespace Tests\Feature;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RidesTest extends TestCase
{
    use RefreshDatabase;

    private function createRide(User $driver, array $overrides = []): Ride
    {
        return $driver->ridesOffered()->create([
            'origin' => 'Downtown',
            'destination' => 'Convention Center',
            'departure_at' => now()->addDay(),
            'seats_total' => 2,
            ...$overrides,
        ]);
    }

    public function test_guests_can_browse_rides(): void
    {
        $driver = User::factory()->create();
        $ride = $this->createRide($driver);

        $this->get('/rides')->assertOk();
        $this->get(route('rides.show', $ride))->assertOk();
    }

    public function test_user_can_offer_a_ride(): void
    {
        $driver = User::factory()->create();

        $response = $this
            ->actingAs($driver)
            ->post('/rides', [
                'origin' => 'Airport',
                'destination' => 'Conference Hotel',
                'departure_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
                'seats_total' => 3,
            ]);

        $ride = Ride::first();

        $response->assertRedirect(route('rides.show', $ride));
        $this->assertSame($driver->id, $ride->driver_id);
        $this->assertSame(3, $ride->seats_total);
    }

    public function test_user_can_book_and_cancel_a_seat(): void
    {
        $driver = User::factory()->create();
        $passenger = User::factory()->create();
        $ride = $this->createRide($driver);

        $this->actingAs($passenger)
            ->post(route('rides.bookings.store', $ride), ['seats_booked' => 1])
            ->assertRedirect(route('rides.show', $ride));

        $this->assertTrue($ride->hasBooking($passenger));
        $this->assertSame(1, $ride->seatsBooked());

        $this->actingAs($passenger)->delete(route('rides.bookings.destroy', $ride))
            ->assertRedirect(route('rides.show', $ride));

        $this->assertFalse($ride->hasBooking($passenger));
    }

    public function test_booking_is_rejected_when_not_enough_seats_remain(): void
    {
        $driver = User::factory()->create();
        $passenger = User::factory()->create();
        $ride = $this->createRide($driver, ['seats_total' => 1]);

        $this->actingAs($passenger)
            ->post(route('rides.bookings.store', $ride), ['seats_booked' => 2]);

        $this->assertFalse($ride->hasBooking($passenger));
    }

    public function test_driver_cannot_book_their_own_ride(): void
    {
        $driver = User::factory()->create();
        $ride = $this->createRide($driver);

        $this->actingAs($driver)
            ->post(route('rides.bookings.store', $ride), ['seats_booked' => 1])
            ->assertForbidden();
    }
}
