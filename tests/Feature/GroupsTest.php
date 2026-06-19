<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class GroupsTest extends TestCase
{
    use RefreshDatabase;

    private function createGroup(User $owner): Group
    {
        $group = $owner->ownedGroups()->create([
            'name' => 'Indie Hackers',
            'slug' => 'indie-hackers-'.Str::random(6),
            'description' => 'A place to discuss bootstrapped businesses.',
        ]);

        $group->members()->attach($owner);

        return $group;
    }

    public function test_guests_can_browse_groups(): void
    {
        $owner = User::factory()->create();
        $group = $this->createGroup($owner);

        $this->get('/groups')->assertOk();
        $this->get(route('groups.show', $group))->assertOk();
    }

    public function test_user_can_create_a_group_and_is_added_as_a_member(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/groups', [
                'name' => 'Indie Hackers',
                'description' => 'A place to discuss bootstrapped businesses.',
            ]);

        $group = Group::first();

        $response->assertRedirect(route('groups.show', $group));
        $this->assertSame($user->id, $group->owner_id);
        $this->assertTrue($group->hasMember($user));
    }

    public function test_user_can_join_and_leave_a_group(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $group = $this->createGroup($owner);

        $this->actingAs($member)->post(route('groups.join', $group))
            ->assertRedirect(route('groups.show', $group));

        $this->assertTrue($group->hasMember($member));

        $this->actingAs($member)->delete(route('groups.leave', $group))
            ->assertRedirect(route('groups.show', $group));

        $this->assertFalse($group->hasMember($member));
    }

    public function test_owner_cannot_leave_their_own_group(): void
    {
        $owner = User::factory()->create();
        $group = $this->createGroup($owner);

        $this->actingAs($owner)->delete(route('groups.leave', $group))
            ->assertForbidden();
    }

    public function test_only_members_can_post_in_a_group(): void
    {
        $owner = User::factory()->create();
        $outsider = User::factory()->create();
        $group = $this->createGroup($owner);

        $this->actingAs($outsider)
            ->post(route('group-posts.store', $group), ['body' => 'Hello'])
            ->assertForbidden();

        $this->actingAs($owner)
            ->post(route('group-posts.store', $group), ['body' => 'Welcome everyone'])
            ->assertRedirect(route('groups.show', $group));

        $this->assertSame(1, $group->posts()->count());
    }

    public function test_only_members_can_comment_on_a_group_post(): void
    {
        $owner = User::factory()->create();
        $outsider = User::factory()->create();
        $group = $this->createGroup($owner);

        $post = $group->posts()->create(['user_id' => $owner->id, 'body' => 'Welcome everyone']);

        $this->actingAs($outsider)
            ->post(route('group-posts.comments.store', $post), ['body' => 'Nice!'])
            ->assertForbidden();

        $this->actingAs($owner)
            ->post(route('group-posts.comments.store', $post), ['body' => 'Thanks!'])
            ->assertRedirect(route('groups.show', $group));

        $this->assertSame(1, $post->comments()->count());
    }
}
