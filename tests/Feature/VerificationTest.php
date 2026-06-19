<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_application_persists_pending_status(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/seller/apply', [
            'name' => 'My Store',
            'description' => 'A nice store.',
        ]);

        $user->refresh();

        $this->assertTrue($user->is_seller);
        $this->assertSame('pending', $user->seller_status);
        $this->assertSame('pending', $user->store->status);
    }

    public function test_admin_can_approve_a_seller_application(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create(['is_seller' => true, 'seller_status' => 'pending']);
        $seller->store()->create(['name' => 'Store', 'slug' => 'store-'.Str::random(6)]);

        $this->actingAs($admin)->post(route('admin.verification.sellers.approve', $seller));

        $this->assertSame('approved', $seller->fresh()->seller_status);
        $this->assertSame('approved', $seller->store->fresh()->status);
        $this->assertTrue($seller->fresh()->isApprovedSeller());
    }

    public function test_admin_can_reject_a_seller_application(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create(['is_seller' => true, 'seller_status' => 'pending']);
        $seller->store()->create(['name' => 'Store', 'slug' => 'store-'.Str::random(6)]);

        $this->actingAs($admin)->post(route('admin.verification.sellers.reject', $seller));

        $seller->refresh();
        $this->assertSame('rejected', $seller->seller_status);
        $this->assertFalse($seller->is_seller);
        $this->assertSame('rejected', $seller->store->fresh()->status);
    }

    public function test_tutor_application_persists_pending_status(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/tutor/apply', [
            'expertise' => 'Marketing for 10 years.',
        ]);

        $user->refresh();

        $this->assertTrue($user->is_tutor);
        $this->assertSame('pending', $user->tutor_status);
    }

    public function test_admin_can_approve_and_reject_a_tutor_application(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tutor = User::factory()->create(['is_tutor' => true, 'tutor_status' => 'pending']);

        $this->actingAs($admin)->post(route('admin.verification.tutors.approve', $tutor));
        $this->assertSame('approved', $tutor->fresh()->tutor_status);

        $this->actingAs($admin)->post(route('admin.verification.tutors.reject', $tutor));
        $tutor->refresh();
        $this->assertSame('rejected', $tutor->tutor_status);
        $this->assertFalse($tutor->is_tutor);
    }

    public function test_tutor_can_submit_a_course_and_admin_can_approve_it(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tutor = User::factory()->create(['is_tutor' => true, 'tutor_status' => 'approved']);

        $course = $tutor->courses()->create([
            'title' => 'Intro to Sales',
            'slug' => 'intro-to-sales-'.Str::random(6),
            'price_cents' => 0,
            'status' => 'draft',
        ]);
        $course->lessons()->create(['title' => 'Lesson 1', 'content' => 'Welcome.', 'position' => 1]);

        $this->actingAs($tutor)->post(route('tutor.courses.submit', $course));

        $this->assertSame('pending', $course->fresh()->status);

        $this->actingAs($admin)->post(route('admin.verification.courses.approve', $course));

        $this->assertSame('approved', $course->fresh()->status);
        $this->assertTrue($course->fresh()->isApproved());
    }
}
