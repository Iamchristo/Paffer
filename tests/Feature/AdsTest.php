<?php

namespace Tests\Feature;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_an_ad_for_review(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('ads.store'), [
                'title' => 'Check out my shop',
                'body' => 'Handmade goods, locally sourced.',
                'target_url' => 'https://example.com',
            ])
            ->assertRedirect(route('ads.index'));

        $ad = Ad::first();

        $this->assertSame($user->id, $ad->advertiser_id);
        $this->assertSame('pending', $ad->status);
    }

    public function test_admin_can_approve_and_reject_an_ad(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $advertiser = User::factory()->create();
        $ad = $advertiser->ads()->create(['title' => 'My Ad', 'status' => 'pending']);

        $this->actingAs($admin)
            ->post(route('admin.verification.ads.approve', $ad))
            ->assertRedirect();

        $this->assertSame('approved', $ad->fresh()->status);

        $this->actingAs($admin)
            ->post(route('admin.verification.ads.reject', $ad))
            ->assertRedirect();

        $this->assertSame('rejected', $ad->fresh()->status);
    }

    public function test_non_admin_cannot_approve_an_ad(): void
    {
        $user = User::factory()->create();
        $advertiser = User::factory()->create();
        $ad = $advertiser->ads()->create(['title' => 'My Ad', 'status' => 'pending']);

        $this->actingAs($user)
            ->post(route('admin.verification.ads.approve', $ad))
            ->assertForbidden();
    }

    public function test_approved_ads_appear_on_the_network_feed(): void
    {
        $advertiser = User::factory()->create();
        $ad = $advertiser->ads()->create(['title' => 'Sponsored Widget', 'status' => 'approved']);

        $viewer = User::factory()->create();

        $this->actingAs($viewer)
            ->get(route('network.feed'))
            ->assertOk()
            ->assertSee('Sponsored Widget');
    }

    public function test_user_can_delete_their_own_ad_but_not_others(): void
    {
        $owner = User::factory()->create();
        $ad = $owner->ads()->create(['title' => 'My Ad', 'status' => 'pending']);

        $other = User::factory()->create();

        $this->actingAs($other)
            ->delete(route('ads.destroy', $ad))
            ->assertForbidden();

        $this->actingAs($owner)
            ->delete(route('ads.destroy', $ad))
            ->assertRedirect();

        $this->assertNull(Ad::find($ad->id));
    }
}
