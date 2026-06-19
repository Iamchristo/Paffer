<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_wallet_is_created_automatically_for_new_users(): void
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->wallet);
        $this->assertSame(0, $user->wallet->balance_cents);
    }

    public function test_user_can_request_a_topup(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('wallet.topup'), ['amount' => '25.00'])->assertRedirect();

        $this->assertDatabaseHas('wallet_transactions', [
            'wallet_id' => $user->wallet->id,
            'type' => 'topup',
            'amount_cents' => 2500,
            'status' => 'pending',
        ]);
        $this->assertSame(0, $user->wallet->fresh()->balance_cents);
    }

    public function test_admin_can_approve_a_topup_request(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $transaction = $user->wallet->transactions()->create([
            'type' => 'topup',
            'amount_cents' => 5000,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)->post(route('admin.wallets.topups.approve', $transaction))->assertRedirect();

        $this->assertSame(5000, $user->wallet->fresh()->balance_cents);
        $this->assertSame('completed', $transaction->fresh()->status);
    }

    public function test_admin_can_reject_a_topup_request(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $transaction = $user->wallet->transactions()->create([
            'type' => 'topup',
            'amount_cents' => 5000,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)->post(route('admin.wallets.topups.reject', $transaction))->assertRedirect();

        $this->assertSame(0, $user->wallet->fresh()->balance_cents);
        $this->assertSame('rejected', $transaction->fresh()->status);
    }

    public function test_admin_can_manually_adjust_a_wallet_balance(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $this->actingAs($admin)->post(route('admin.wallets.adjust', $user), ['amount' => '-10'])->assertRedirect();

        $this->assertSame(-1000, $user->wallet->fresh()->balance_cents);
    }

    public function test_checkout_can_be_paid_with_wallet_balance(): void
    {
        $buyer = User::factory()->create();
        $buyer->wallet->update(['balance_cents' => 10000]);

        $seller = User::factory()->create(['is_seller' => true, 'seller_status' => 'approved']);
        $store = $seller->store()->create(['name' => 'Store', 'slug' => 'store-'.Str::random(6), 'status' => 'approved']);
        $product = $store->products()->create(['name' => 'Widget', 'slug' => 'widget-'.Str::random(6), 'price_cents' => 2000, 'stock' => 5, 'status' => 'active']);

        $this->actingAs($buyer)->post(route('cart.store', $product), ['quantity' => 2]);

        $this->actingAs($buyer)->post(route('checkout.store'), ['pay_with_wallet' => '1'])->assertRedirect(route('orders.index'));

        $this->assertSame(6000, $buyer->wallet->fresh()->balance_cents);
        $this->assertDatabaseHas('orders', ['buyer_id' => $buyer->id, 'status' => 'processing', 'total_cents' => 4000]);
        $this->assertDatabaseHas('wallet_transactions', ['wallet_id' => $buyer->wallet->id, 'type' => 'payment', 'amount_cents' => -4000]);
    }

    public function test_checkout_with_wallet_fails_when_balance_is_insufficient(): void
    {
        $buyer = User::factory()->create();

        $seller = User::factory()->create(['is_seller' => true, 'seller_status' => 'approved']);
        $store = $seller->store()->create(['name' => 'Store', 'slug' => 'store-'.Str::random(6), 'status' => 'approved']);
        $product = $store->products()->create(['name' => 'Widget', 'slug' => 'widget-'.Str::random(6), 'price_cents' => 2000, 'stock' => 5, 'status' => 'active']);

        $this->actingAs($buyer)->post(route('cart.store', $product), ['quantity' => 2]);

        $this->actingAs($buyer)->post(route('checkout.store'), ['pay_with_wallet' => '1'])->assertRedirect();

        $this->assertDatabaseMissing('orders', ['buyer_id' => $buyer->id]);
        $this->assertSame(0, $buyer->wallet->fresh()->balance_cents);
    }
}
