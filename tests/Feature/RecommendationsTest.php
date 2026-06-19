<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RecommendationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_recommendations_settings_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.recommendations.edit'))->assertOk();
    }

    public function test_non_admin_cannot_view_recommendations_settings_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.recommendations.edit'))->assertForbidden();
    }

    public function test_admin_can_toggle_recommendation_surfaces(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->put(route('admin.recommendations.update'), [
            'recommend_on_feed' => '0',
            'recommend_on_marketplace' => '1',
            'recommend_on_learn' => '0',
        ])->assertRedirect();

        $this->assertFalse(Setting::getBool('recommend_on_feed', true));
        $this->assertTrue(Setting::getBool('recommend_on_marketplace', true));
        $this->assertFalse(Setting::getBool('recommend_on_learn', true));
    }

    public function test_disabling_marketplace_recommendations_hides_them_from_the_catalog(): void
    {
        Setting::set('recommend_on_marketplace', false);

        $tutor = User::factory()->create(['is_tutor' => true, 'tutor_status' => 'approved']);
        $course = $tutor->courses()->create([
            'title' => 'PHP Basics',
            'slug' => 'php-basics-'.Str::random(6),
            'price_cents' => 0,
        ]);
        $course->status = 'approved';
        $course->save();

        $buyer = User::factory()->create();

        $response = $this->actingAs($buyer)->get(route('marketplace.index'));

        $response->assertOk();
        $this->assertTrue($response->viewData('recommendedProducts')->isEmpty());
    }

    public function test_learn_catalog_exposes_recommended_courses_when_enabled(): void
    {
        Setting::set('recommend_on_learn', true);

        $tutor = User::factory()->create(['is_tutor' => true, 'tutor_status' => 'approved']);
        $course = $tutor->courses()->create([
            'title' => 'Advanced PHP',
            'slug' => 'advanced-php-'.Str::random(6),
            'price_cents' => 0,
        ]);
        $course->status = 'approved';
        $course->save();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('learn.index'));

        $response->assertOk();
        $this->assertNotNull($response->viewData('recommendedCourses'));
    }
}
