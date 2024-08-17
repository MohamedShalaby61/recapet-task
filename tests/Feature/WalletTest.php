<?php
    // tests/Feature/WalletTest.php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up test data
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $this->user->wallet()->create(['balance' => 50.00]);
    }

    public function test_user_can_get_balance()
    {
        $response = $this->actingAs($this->user)->getJson('/api/balance');

        $response->assertStatus(200);
        $response->assertJsonStructure(['balance']);
        $response->assertJson(['balance' => 50.00]);
    }

    public function test_user_can_top_up_wallet()
    {
        $response = $this->actingAs($this->user)->postJson('/api/topup', [
            'amount' => 100.00,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Top-up successful']);

        // Check the wallet balance after top-up
        $this->assertDatabaseHas('wallets', [
            'user_id' => $this->user->id,
            'balance' => 150.00, // 50 + 100
        ]);
    }

    public function test_user_cannot_top_up_wallet_with_invalid_amount()
    {
        $response = $this->actingAs($this->user)->postJson('/api/topup', [
            'amount' => -10.00, // Invalid amount
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['amount']);
    }
}
