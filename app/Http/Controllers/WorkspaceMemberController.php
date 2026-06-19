<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkspaceMemberController extends Controller
{
    public function store(Request $request, Workspace $workspace): RedirectResponse
    {
        abort_unless($workspace->hasMember($request->user()), 403);

        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $invitee = User::where('email', $data['email'])->first();

        if (! $invitee) {
            return back()->with('error', 'workspace-member-not-found');
        }

        $workspace->members()->syncWithoutDetaching([$invitee->id => ['role' => 'member']]);

        return back()->with('status', 'workspace-member-added');
    }

    public function destroy(Request $request, Workspace $workspace, User $user): RedirectResponse
    {
        abort_unless($workspace->hasMember($request->user()), 403);
        abort_if($user->id === $workspace->owner_id, 403);
        abort_unless($user->id === $request->user()->id || $workspace->owner_id === $request->user()->id, 403);

        $workspace->members()->detach($user);

        return back()->with('status', 'workspace-member-removed');
    }
}
