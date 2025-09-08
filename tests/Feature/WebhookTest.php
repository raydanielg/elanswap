<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_processes_payment_webhook()
    {
        // Create a test user
        $user = User::factory()->create();
        
        // Create a test payment
        $payment = Payment::create([
            'user_id' => $user->id,
            'method' => 'mpesa',
            'amount' => 2500,
            'currency' => 'TZS',
            'provider_reference' => 'TEST' . time(),
            'meta' => [
                'order_id' => 'TEST' . time(),
                'phone' => '255700000000',
                'provider' => 'test',
            ]
        ]);

        // Get the order ID from the payment
        $orderId = $payment->meta['order_id'];
        
        // Prepare webhook data
        $webhookData = [
            'order_id' => $orderId,
            'status' => 'paid',
            'transid' => 'TXN' . time(),
            'reference' => $payment->provider_reference,
            'amount' => '2500',
            'currency' => 'TZS',
            'timestamp' => now()->toDateTimeString()
        ];

        // Send webhook request
        $response = $this->postJson('/payment/webhook', $webhookData);

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Payment status updated',
                'order_id' => $orderId
            ]);

        // Assert payment was updated
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'paid_at' => now()->toDateTimeString(),
        ]);
    }

    /** @test */
    public function it_handles_missing_fields()
    {
        $response = $this->postJson('/payment/webhook', [
            'order_id' => 'TEST123',
            // Missing status, transid, reference
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'message' => 'Missing required fields'
            ]);
    }

    /** @test */
    public function it_handles_nonexistent_payment()
    {
        $response = $this->postJson('/payment/webhook', [
            'order_id' => 'NONEXISTENT' . time(),
            'status' => 'paid',
            'transid' => 'TXN' . time(),
            'reference' => 'NONEXISTENT' . time(),
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Payment not found'
            ]);
    }
}
