<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $conversations = Conversation::query()
            ->where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['userOne.profile', 'userTwo.profile', 'latestMessage'])
            ->orderByDesc('last_message_at')
            ->get();

        return view('messages.index', [
            'conversations' => $conversations,
            'user' => $user,
        ]);
    }

    public function show(Request $request, User $user): View
    {
        $authUser = $request->user();

        abort_if($authUser->id === $user->id, 403);

        $conversation = Conversation::between($authUser, $user);

        $conversation->messages()
            ->where('sender_id', '!=', $authUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', [
            'otherUser' => $user,
            'messages' => $conversation->messages()->with('sender')->oldest()->get(),
        ]);
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        $authUser = $request->user();

        abort_if($authUser->id === $user->id, 403);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $conversation = Conversation::between($authUser, $user);

        $conversation->messages()->create([
            'sender_id' => $authUser->id,
            'body' => $validated['body'],
        ]);

        $conversation->last_message_at = now();
        $conversation->save();

        return redirect()->route('messages.show', $user);
    }
}
