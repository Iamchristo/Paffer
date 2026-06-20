<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminOrdersAndAdsTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_orders(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.orders.index'))->assertForbidden();
    }

    public function test_admin_can_view_the_orders_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $buyer = User::factory()->create(['name' => 'Order Buyer']);
        $seller = User::factory()->create(['is_seller' => true, 'seller_status' => 'approved']);
        $store = $seller->store()->create(['name' => 'Store', 'slug' => 'store-'.Str::random(6), 'status' => 'approved']);
        $buyer->orders()->create(['store_id' => $store->id, 'status' => 'pending', 'total_cents' => 5000]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index'));

        $response->assertOk();
        $response->assertSee('Order Buyer');
    }

    public function test_admin_orders_list_can_be_filtered_by_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create(['is_seller' => true, 'seller_status' => 'approved']);
        $store = $seller->store()->create(['name' => 'Store', 'slug' => 'store-'.Str::random(6), 'status' => 'approved']);

        $pendingBuyer = User::factory()->create(['name' => 'Pending Buyer']);
        $pendingBuyer->orders()->create(['store_id' => $store->id, 'status' => 'pending', 'total_cents' => 1000]);

        $completedBuyer = User::factory()->create(['name' => 'Completed Buyer']);
        $completedBuyer->orders()->create(['store_id' => $store->id, 'status' => 'completed', 'total_cents' => 2000]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index', ['status' => 'completed']));

        $response->assertOk();
        $response->assertSee('Completed Buyer');
        $response->assertDontSee('Pending Buyer');
    }

    public function test_admin_can_view_an_order_detail_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $buyer = User::factory()->create();
        $seller = User::factory()->create(['is_seller' => true, 'seller_status' => 'approved']);
        $store = $seller->store()->create(['name' => 'Store', 'slug' => 'store-'.Str::random(6), 'status' => 'approved']);
        $product = $store->products()->create(['name' => 'Widget', 'slug' => 'widget-'.Str::random(6), 'price_cents' => 1500, 'stock' => 5, 'status' => 'active']);
        $order = $buyer->orders()->create(['store_id' => $store->id, 'status' => 'completed', 'total_cents' => 1500]);
        $order->items()->create(['product_id' => $product->id, 'quantity' => 1, 'price_cents' => 1500]);

        $response = $this->actingAs($admin)->get(route('admin.orders.show', $order));

        $response->assertOk();
        $response->assertSee('Widget');
    }

    public function test_non_admin_cannot_access_admin_ads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.ads.index'))->assertForbidden();
    }

    public function test_admin_can_view_all_ads_regardless_of_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $advertiser = User::factory()->create();
        $advertiser->ads()->create(['title' => 'Approved Ad', 'body' => 'Body', 'status' => 'approved']);
        $advertiser->ads()->create(['title' => 'Rejected Ad', 'body' => 'Body', 'status' => 'rejected']);

        $response = $this->actingAs($admin)->get(route('admin.ads.index'));

        $response->assertOk();
        $response->assertSee('Approved Ad');
        $response->assertSee('Rejected Ad');
    }

    public function test_admin_can_approve_an_ad_from_the_ads_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $advertiser = User::factory()->create();
        $ad = $advertiser->ads()->create(['title' => 'Pending Ad', 'body' => 'Body', 'status' => 'pending']);

        $this->actingAs($admin)->post(route('admin.ads.approve', $ad))->assertRedirect();

        $this->assertSame('approved', $ad->fresh()->status);
    }

    public function test_admin_can_revoke_an_approved_ad(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $advertiser = User::factory()->create();
        $ad = $advertiser->ads()->create(['title' => 'Approved Ad', 'body' => 'Body', 'status' => 'approved']);

        $this->actingAs($admin)->post(route('admin.ads.reject', $ad))->assertRedirect();

        $this->assertSame('rejected', $ad->fresh()->status);
    }

    public function test_admin_can_delete_an_ad(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $advertiser = User::factory()->create();
        $ad = $advertiser->ads()->create(['title' => 'Some Ad', 'body' => 'Body', 'status' => 'pending']);

        $this->actingAs($admin)->delete(route('admin.ads.destroy', $ad))->assertRedirect();

        $this->assertDatabaseMissing('ads', ['id' => $ad->id]);
    }
}
