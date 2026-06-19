<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_mobile_settings_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.mobile.edit'))->assertOk();
    }

    public function test_non_admin_cannot_view_mobile_settings_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.mobile.edit'))->assertForbidden();
    }

    public function test_admin_can_update_mobile_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->put(route('admin.mobile.update'), [
            'pwa_enabled' => '1',
            'pwa_short_name' => 'PAFFAR Go',
            'pwa_theme_color' => '#112233',
        ])->assertRedirect();

        $this->assertTrue(Setting::getBool('pwa_enabled'));
        $this->assertSame('PAFFAR Go', Setting::get('pwa_short_name'));
        $this->assertSame('#112233', Setting::get('pwa_theme_color'));
    }

    public function test_updating_mobile_settings_rejects_an_invalid_theme_color(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->put(route('admin.mobile.update'), [
            'pwa_short_name' => 'PAFFAR Go',
            'pwa_theme_color' => 'not-a-color',
        ])->assertInvalid(['pwa_theme_color']);
    }

    public function test_manifest_route_reflects_saved_settings(): void
    {
        Setting::set('pwa_short_name', 'PAFFAR Go');
        Setting::set('pwa_theme_color', '#112233');

        $response = $this->get(route('pwa.manifest'));

        $response->assertOk();
        $response->assertJsonPath('short_name', 'PAFFAR Go');
        $response->assertJsonPath('theme_color', '#112233');
    }
}
