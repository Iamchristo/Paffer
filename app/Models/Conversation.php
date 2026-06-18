<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_one_id', 'user_two_id'])]
class Conversation extends Model
{
    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function otherUser(User $viewer): User
    {
        return $this->user_one_id === $viewer->id ? $this->userTwo : $this->userOne;
    }

    public function unreadCountFor(User $viewer): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $viewer->id)
            ->whereNull('read_at')
            ->count();
    }

    public static function between(User $a, User $b): self
    {
        [$userOneId, $userTwoId] = $a->id < $b->id ? [$a->id, $b->id] : [$b->id, $a->id];

        return static::firstOrCreate([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
        ]);
    }
}
