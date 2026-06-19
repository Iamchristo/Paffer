<?php

namespace Tests\Feature;

use App\Mail\AdminAnnouncement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private string $envBackup;

    protected function setUp(): void
    {
        parent::setUp();

        $this->envBackup = file_get_contents(base_path('.env'));
    }

    protected function tearDown(): void
    {
        file_put_contents(base_path('.env'), $this->envBackup);

        parent::tearDown();
    }

    public function test_admin_can_update_mail_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
            'mailer' => 'log',
            'host' => 'smtp.example.com',
            'port' => '587',
            'username' => 'mailer@example.com',
            'password' => 'secret',
            'encryption' => 'tls',
            'from_address' => 'noreply@example.com',
            'from_name' => 'Paffer',
        ]);

        $response->assertRedirect();
        $this->assertStringContainsString('MAIL_HOST=smtp.example.com', file_get_contents(base_path('.env')));
    }

    public function test_non_admin_cannot_access_settings(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.settings.edit'))->assertForbidden();
    }

    public function test_admin_can_send_an_announcement_to_all_users(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(3)->create();

        $this->actingAs($admin)->post(route('admin.announcements.store'), [
            'audience' => 'all',
            'subject' => 'Platform update',
            'body' => 'We shipped new features.',
        ])->assertRedirect();

        Mail::assertSent(AdminAnnouncement::class, 4);
        $this->assertDatabaseHas('announcements', [
            'subject' => 'Platform update',
            'audience' => 'all',
            'recipient_count' => 4,
        ]);
    }

    public function test_admin_can_suspend_a_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $this->actingAs($admin)->post(route('admin.users.suspend', $user))->assertRedirect();

        $this->assertTrue($user->fresh()->is_suspended);
    }

    public function test_suspended_user_cannot_log_in(): void
    {
        $user = User::factory()->create(['is_suspended' => true]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_can_promote_a_user_to_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $this->actingAs($admin)->post(route('admin.users.toggle-admin', $user))->assertRedirect();

        $this->assertTrue($user->fresh()->isAdmin());
    }

    public function test_admin_can_manually_set_seller_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $this->actingAs($admin)->post(route('admin.users.seller-status', $user), [
            'seller_status' => 'approved',
        ])->assertRedirect();

        $user->refresh();
        $this->assertTrue($user->is_seller);
        $this->assertSame('approved', $user->seller_status);
    }

    public function test_admin_can_delete_a_post_from_the_moderation_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $post = User::factory()->create()->posts()->create(['body' => 'Hello world']);

        $this->actingAs($admin)->delete(route('admin.moderation.posts.destroy', $post))->assertRedirect();

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_admin_can_suspend_a_store_without_deleting_its_products(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create(['is_seller' => true, 'seller_status' => 'approved']);
        $store = $seller->store()->create(['name' => 'Store', 'slug' => 'store-'.Str::random(6), 'status' => 'approved']);
        $product = $store->products()->create(['name' => 'Widget', 'slug' => 'widget', 'price_cents' => 1000]);

        $this->actingAs($admin)->post(route('admin.moderation.stores.suspend', $store))->assertRedirect();

        $this->assertSame('suspended', $store->fresh()->status);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_admin_can_toggle_a_product_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create(['is_seller' => true, 'seller_status' => 'approved']);
        $store = $seller->store()->create(['name' => 'Store', 'slug' => 'store-'.Str::random(6), 'status' => 'approved']);
        $product = $store->products()->create(['name' => 'Widget', 'slug' => 'widget', 'price_cents' => 1000]);

        $this->actingAs($admin)->post(route('admin.moderation.products.toggle', $product))->assertRedirect();

        $this->assertSame('inactive', $product->fresh()->status);
    }

    public function test_admin_can_suspend_an_approved_course(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tutor = User::factory()->create(['is_tutor' => true, 'tutor_status' => 'approved']);
        $course = $tutor->courses()->create([
            'title' => 'Intro',
            'slug' => 'intro-'.Str::random(6),
            'price_cents' => 0,
            'status' => 'approved',
        ]);

        $this->actingAs($admin)->post(route('admin.moderation.courses.suspend', $course))->assertRedirect();

        $this->assertSame('rejected', $course->fresh()->status);
    }

    public function test_admin_users_index_can_be_searched(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['name' => 'Jane Match']);
        User::factory()->create(['name' => 'Someone Else']);

        $response = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'Jane']));

        $response->assertOk();
        $response->assertSee('Jane Match');
        $response->assertDontSee('Someone Else');
    }
}
