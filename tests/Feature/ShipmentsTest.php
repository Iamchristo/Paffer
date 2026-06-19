<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShipmentsTest extends TestCase
{
    use RefreshDatabase;

    private function createSellerWithStore(): User
    {
        $seller = User::factory()->create(['is_seller' => true, 'seller_status' => 'approved']);

        $store = $seller->store()->create([
            'name' => 'Test Store',
            'slug' => 'test-store-'.Str::random(6),
            'description' => 'A test store.',
        ]);
        $store->status = 'approved';
        $store->save();

        return $seller;
    }

    private function createOrder(Store $store, User $buyer): Order
    {
        return $store->orders()->create([
            'buyer_id' => $buyer->id,
            'status' => 'pending',
            'total_cents' => 5000,
        ]);
    }

    public function test_seller_can_create_and_update_a_shipment_for_their_order(): void
    {
        $seller = $this->createSellerWithStore();
        $buyer = User::factory()->create();
        $order = $this->createOrder($seller->store, $buyer);

        $this->actingAs($seller)
            ->put(route('seller.orders.shipment.update', $order), [
                'carrier' => 'UPS',
                'tracking_number' => '1Z999AA10123456784',
                'status' => 'shipped',
            ])
            ->assertRedirect();

        $shipment = $order->shipment()->first();

        $this->assertSame('UPS', $shipment->carrier);
        $this->assertSame('shipped', $shipment->status);
        $this->assertNotNull($shipment->shipped_at);
    }

    public function test_seller_cannot_update_shipment_for_another_stores_order(): void
    {
        $seller = $this->createSellerWithStore();
        $otherSeller = $this->createSellerWithStore();
        $buyer = User::factory()->create();
        $order = $this->createOrder($otherSeller->store, $buyer);

        $this->actingAs($seller)
            ->put(route('seller.orders.shipment.update', $order), ['status' => 'shipped'])
            ->assertForbidden();
    }

    public function test_buyer_can_see_shipment_status_on_their_order_page(): void
    {
        $seller = $this->createSellerWithStore();
        $buyer = User::factory()->create();
        $order = $this->createOrder($seller->store, $buyer);
        $order->shipment()->create(['carrier' => 'FedEx', 'status' => 'in_transit']);

        $this->actingAs($buyer)
            ->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee('FedEx')
            ->assertSee('in transit');
    }
}
