<?php

// tests/Feature/TransactionTest.php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up test data
        $this->sender = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->receiver = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->sender->wallet()->create(['balance' => 100.00]);
        $this->receiver->wallet()->create(['balance' => 50.00]);
    }

    public function test_user_can_transfer_money_successfully()
    {
        $response = $this->actingAs($this->sender)
            ->postJson('/api/transfer', [
                'receiver_id' => $this->receiver->id,
                'amount' => 20.00,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Transfer successful']);

        // Check sender's wallet balance
        $this->assertDatabaseHas('wallets', [
            'user_id' => $this->sender->id,
            'balance' => 77.50, // 100 - 20 - (2.5 + 10% of 20)
        ]);

        // Check receiver's wallet balance
        $this->assertDatabaseHas('wallets', [
            'user_id' => $this->receiver->id,
            'balance' => 70.00, // 50 + 20
        ]);

        // Check the transaction record
        $this->assertDatabaseHas('transactions', [
            'sender_id' => $this->sender->id,
            'receiver_id' => $this->receiver->id,
            'amount' => 20.00,
            'fee' => 4.50, // 2.5 + (10% of 20)
        ]);
    }

    public function test_user_cannot_transfer_money_with_insufficient_balance()
    {
        $response = $this->actingAs($this->sender)
            ->postJson('/api/transfer', [
                'receiver_id' => $this->receiver->id,
                'amount' => 120.00, // Exceeds balance
            ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => 'Insufficient balance to cover the fee']);

        // Ensure wallet balances are unchanged
        $this->assertDatabaseHas('wallets', [
            'user_id' => $this->sender->id,
            'balance' => 100.00,
        ]);

        $this->assertDatabaseHas('wallets', [
            'user_id' => $this->receiver->id,
            'balance' => 50.00,
        ]);
    }

    public function test_user_cannot_transfer_money_to_non_existent_user()
    {
        $response = $this->actingAs($this->sender)
            ->postJson('/api/transfer', [
                'receiver_id' => 999, // Non-existent user ID
                'amount' => 20.00,
            ]);

        $response->assertStatus(404); // Assuming you handle non-existent user with a 404 status
        $response->assertJson(['message' => 'User not found']);

        // Ensure wallet balances are unchanged
        $this->assertDatabaseHas('wallets', [
            'user_id' => $this->sender->id,
            'balance' => 100.00,
        ]);

        $this->assertDatabaseHas('wallets', [
            'user_id' => $this->receiver->id,
            'balance' => 50.00,
        ]);
    }
}
