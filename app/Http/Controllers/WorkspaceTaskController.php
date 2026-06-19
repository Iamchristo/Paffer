<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\WorkspaceTask;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkspaceTaskController extends Controller
{
    public function store(Request $request, Workspace $workspace): RedirectResponse
    {
        abort_unless($workspace->hasMember($request->user()), 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'assignee_id' => ['nullable', 'integer', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
        ]);

        $workspace->tasks()->create([
            ...$data,
            'created_by' => $request->user()->id,
        ]);

        return back()->with('status', 'task-created');
    }

    public function update(Request $request, Workspace $workspace, WorkspaceTask $task): RedirectResponse
    {
        abort_unless($workspace->hasMember($request->user()), 403);
        abort_unless($task->workspace_id === $workspace->id, 404);

        $data = $request->validate([
            'status' => ['required', 'in:todo,in_progress,done'],
        ]);

        $task->update($data);

        return back()->with('status', 'task-updated');
    }

    public function destroy(Request $request, Workspace $workspace, WorkspaceTask $task): RedirectResponse
    {
        abort_unless($workspace->hasMember($request->user()), 403);
        abort_unless($task->workspace_id === $workspace->id, 404);

        $task->delete();

        return back()->with('status', 'task-deleted');
    }
}
