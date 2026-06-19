<?php

namespace App\Http\Controllers;

use App\Models\GroupPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GroupPostCommentController extends Controller
{
    public function store(Request $request, GroupPost $groupPost): RedirectResponse
    {
        abort_unless($groupPost->group->hasMember($request->user()), 403);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $groupPost->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return redirect()->route('groups.show', $groupPost->group);
    }
}
