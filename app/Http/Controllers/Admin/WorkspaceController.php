<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkspaceController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $workspaces = Workspace::query()
            ->with('owner')
            ->withCount('members', 'tasks')
            ->when($search, fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.workspaces.index', [
            'workspaces' => $workspaces,
            'search' => $search,
        ]);
    }

    public function show(Workspace $workspace): View
    {
        $workspace->load(['owner', 'members', 'tasks.assignee']);

        return view('admin.workspaces.show', [
            'workspace' => $workspace,
        ]);
    }

    public function archive(Workspace $workspace): RedirectResponse
    {
        $workspace->update(['status' => 'archived']);

        return back()->with('status', 'workspace-archived');
    }

    public function unarchive(Workspace $workspace): RedirectResponse
    {
        $workspace->update(['status' => 'active']);

        return back()->with('status', 'workspace-unarchived');
    }

    public function destroy(Workspace $workspace): RedirectResponse
    {
        $workspace->delete();

        return redirect()->route('admin.workspaces.index')->with('status', 'workspace-deleted');
    }
}
