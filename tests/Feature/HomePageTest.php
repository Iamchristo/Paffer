<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_redirects_to_feed(): void
    {
        $this->get('/')->assertRedirect(route('network.feed'));
    }

    public function test_guest_sees_login_when_guest_feed_is_disabled(): void
    {
        Setting::set('guest_feed_enabled', false);

        $this->get(route('network.feed'))->assertRedirect(route('login'));
    }

    public function test_guest_sees_feed_when_guest_feed_is_enabled(): void
    {
        Setting::set('guest_feed_enabled', true);

        $this->get(route('network.feed'))->assertOk();
    }

    public function test_authenticated_user_always_sees_feed_regardless_of_setting(): void
    {
        Setting::set('guest_feed_enabled', false);

        $user = User::factory()->create();

        $this->actingAs($user)->get(route('network.feed'))->assertOk();
    }

    public function test_admin_can_enable_guest_feed_visibility(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->put(route('admin.settings.guest-feed.update'), [
            'guest_feed_enabled' => '1',
        ])->assertRedirect();

        $this->assertTrue(Setting::getBool('guest_feed_enabled'));
    }

    public function test_admin_can_disable_guest_feed_visibility(): void
    {
        Setting::set('guest_feed_enabled', true);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->put(route('admin.settings.guest-feed.update'), [])->assertRedirect();

        $this->assertFalse(Setting::getBool('guest_feed_enabled'));
    }

    public function test_non_admin_cannot_update_guest_feed_visibility(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('admin.settings.guest-feed.update'), [
            'guest_feed_enabled' => '1',
        ])->assertForbidden();
    }
}
