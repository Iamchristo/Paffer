<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GroupPostController extends Controller
{
    public function store(Request $request, Group $group): RedirectResponse
    {
        abort_unless($group->hasMember($request->user()), 403);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $group->posts()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return redirect()->route('groups.show', $group);
    }
}
