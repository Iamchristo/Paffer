<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
{
    public function store(Request $request, Group $group): RedirectResponse
    {
        $request->user()->groups()->syncWithoutDetaching([$group->id]);

        return redirect()->route('groups.show', $group)->with('status', 'group-joined');
    }

    public function destroy(Request $request, Group $group): RedirectResponse
    {
        abort_if($group->owner_id === $request->user()->id, 403);

        $request->user()->groups()->detach($group);

        return redirect()->route('groups.show', $group)->with('status', 'group-left');
    }
}
