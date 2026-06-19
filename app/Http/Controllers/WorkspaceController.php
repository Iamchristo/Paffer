<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WorkspaceController extends Controller
{
    public function index(Request $request): View
    {
        $workspaces = $request->user()->workspaces()
            ->withCount('members', 'tasks')
            ->latest('workspaces.created_at')
            ->paginate(12);

        return view('workspaces.index', [
            'workspaces' => $workspaces,
        ]);
    }

    public function create(): View
    {
        return view('workspaces.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $workspace = $request->user()->ownedWorkspaces()->create([
            ...$validated,
            'slug' => Str::slug($validated['name']).'-'.Str::random(6),
        ]);

        $workspace->members()->attach($request->user(), ['role' => 'owner']);

        return redirect()->route('workspaces.show', $workspace)->with('status', 'workspace-created');
    }

    public function show(Request $request, Workspace $workspace): View
    {
        abort_unless($workspace->hasMember($request->user()), 403);

        $workspace->load(['owner', 'members', 'tasks.assignee', 'tasks.creator']);

        return view('workspaces.show', [
            'workspace' => $workspace,
            'tasksByStatus' => $workspace->tasks->groupBy('status'),
        ]);
    }

    public function destroy(Request $request, Workspace $workspace): RedirectResponse
    {
        abort_unless($workspace->owner_id === $request->user()->id, 403);

        $workspace->delete();

        return redirect()->route('workspaces.index')->with('status', 'workspace-deleted');
    }
}
