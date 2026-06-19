<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'slug', 'description', 'location', 'starts_at', 'ends_at', 'capacity'])]
class Event extends Model
{
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_attendees')->withTimestamps();
    }

    public function rides(): HasMany
    {
        return $this->hasMany(Ride::class);
    }

    public function hasAttendee(User $user): bool
    {
        return $this->attendees()->whereKey($user->id)->exists();
    }

    public function isFull(): bool
    {
        return $this->capacity !== null && $this->attendees()->count() >= $this->capacity;
    }
}
