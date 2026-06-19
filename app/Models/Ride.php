<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['origin', 'destination', 'departure_at', 'seats_total', 'notes', 'event_id'])]
class Ride extends Model
{
    protected function casts(): array
    {
        return ['departure_at' => 'datetime'];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(RideBooking::class);
    }

    public function seatsBooked(): int
    {
        return (int) $this->bookings()->sum('seats_booked');
    }

    public function seatsAvailable(): int
    {
        return max(0, $this->seats_total - $this->seatsBooked());
    }

    public function hasBooking(User $user): bool
    {
        return $this->bookings()->where('passenger_id', $user->id)->exists();
    }
}
