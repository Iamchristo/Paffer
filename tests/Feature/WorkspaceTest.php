<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_workspace(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('workspaces.store'), [
            'name' => 'Launch Plan',
            'description' => 'Plan the launch.',
        ]);

        $workspace = Workspace::first();

        $response->assertRedirect(route('workspaces.show', $workspace));
        $this->assertSame($user->id, $workspace->owner_id);
        $this->assertTrue($workspace->hasMember($user));
        $this->assertSame('owner', $workspace->members->first()->pivot->role);
    }

    public function test_non_member_cannot_view_a_workspace(): void
    {
        $owner = User::factory()->create();
        $outsider = User::factory()->create();
        $workspace = $owner->ownedWorkspaces()->create(['name' => 'Private', 'slug' => 'private-ws']);
        $workspace->members()->attach($owner, ['role' => 'owner']);

        $this->actingAs($outsider)->get(route('workspaces.show', $workspace))->assertForbidden();
    }

    public function test_member_can_invite_another_user_by_email(): void
    {
        $owner = User::factory()->create();
        $invitee = User::factory()->create();
        $workspace = $owner->ownedWorkspaces()->create(['name' => 'Team', 'slug' => 'team-ws']);
        $workspace->members()->attach($owner, ['role' => 'owner']);

        $this->actingAs($owner)->post(route('workspace-members.store', $workspace), [
            'email' => $invitee->email,
        ])->assertRedirect();

        $this->assertTrue($workspace->hasMember($invitee->fresh()));
    }

    public function test_member_can_create_and_update_a_task(): void
    {
        $owner = User::factory()->create();
        $workspace = $owner->ownedWorkspaces()->create(['name' => 'Team', 'slug' => 'team-ws-2']);
        $workspace->members()->attach($owner, ['role' => 'owner']);

        $this->actingAs($owner)->post(route('workspace-tasks.store', $workspace), [
            'title' => 'Design homepage',
        ])->assertRedirect();

        $task = $workspace->tasks()->first();
        $this->assertSame('todo', $task->status);
        $this->assertSame($owner->id, $task->created_by);

        $this->actingAs($owner)->patch(route('workspace-tasks.update', [$workspace, $task]), [
            'status' => 'done',
        ])->assertRedirect();

        $this->assertSame('done', $task->fresh()->status);
    }

    public function test_only_owner_can_delete_a_workspace(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $workspace = $owner->ownedWorkspaces()->create(['name' => 'Team', 'slug' => 'team-ws-3']);
        $workspace->members()->attach($owner, ['role' => 'owner']);
        $workspace->members()->attach($member, ['role' => 'member']);

        $this->actingAs($member)->delete(route('workspaces.destroy', $workspace))->assertForbidden();
        $this->actingAs($owner)->delete(route('workspaces.destroy', $workspace))->assertRedirect(route('workspaces.index'));
        $this->assertDatabaseMissing('workspaces', ['id' => $workspace->id]);
    }

    public function test_admin_can_archive_and_delete_a_workspace(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner = User::factory()->create();
        $workspace = $owner->ownedWorkspaces()->create(['name' => 'Team', 'slug' => 'team-ws-4']);
        $workspace->members()->attach($owner, ['role' => 'owner']);

        $this->actingAs($admin)->post(route('admin.workspaces.archive', $workspace))->assertRedirect();
        $this->assertSame('archived', $workspace->fresh()->status);

        $this->actingAs($admin)->delete(route('admin.workspaces.destroy', $workspace))->assertRedirect();
        $this->assertDatabaseMissing('workspaces', ['id' => $workspace->id]);
    }
}
