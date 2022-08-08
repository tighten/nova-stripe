<?php

namespace Tighten\NovaStripe\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Tighten\NovaStripe\Clients\StripeClient;

class StripeChargeControllerTest extends TestCase
{
    use WithFaker;

    protected $stripe;

    public function setUp(): void
    {
        parent::setUp();

        $this->stripe = new StripeClient;
    }

    /** @test */
    public function it_can_can_return_a_response()
    {
        $this->get('nova-vendor/nova-stripe/stripe/charges')
            ->assertSuccessful();

        $this->get('nova-vendor/nova-stripe/stripe/balance')
            ->assertSuccessful();

        $this->get('nova-vendor/nova-stripe/stripe/charges/' . 1)
            ->assertSuccessful();

        $this->get('nova-vendor/nova-stripe/stripe/customers')
            ->assertSuccessful();

        $this->get('nova-vendor/nova-stripe/stripe/customers/' . 1)
            ->assertSuccessful();
    }

    /** @test */
    public function it_returns_a_list_of_charges()
    {
        $this->get('nova-vendor/nova-stripe/stripe/charges')
            ->assertJsonStructure([
                'charges' => [
                    'data' => [
                        '*' => [],
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_returns_charge_details()
    {
        $this->get('nova-vendor/nova-stripe/stripe/charges/' . 1)
            ->assertJsonStructure([
            'charge' => [
                'id',
                'amount',
                'status',
                'created',
                'metadata',
                'livemode',
                'captured',
                'paid',
                'refunded',
                'disputed',
                'fraud_details',
                'transfer_group',
            ],
        ]);
    }

    /** @test */
    public function it_shows_the_current_balance()
    {
        $this->get('nova-vendor/nova-stripe/stripe/balance')
            ->assertJsonStructure([
                'balance' => [
                    'available' => [
                        '*' => [
                            'amount',
                            'currency',
                        ],
                    ],
                    'pending' => [
                        '*' => [
                            'amount',
                            'currency',
                        ],
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_can_refund_a_charge_successfully()
    {
        $this->post('nova-vendor/nova-stripe/stripe/charges/' . 1 . '/refund')
            ->assertSuccessful()
            ->assertJsonFragment([
                'status' => 'succeeded',
            ]);
    }

    /** @test */
    public function it_returns_a_list_of_customers()
    {
        $this->get('nova-vendor/nova-stripe/stripe/customers')
            ->assertJsonStructure([
                'customers' => [
                    'data' => [
                        '*' => [],
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_returns_customer_details()
    {
        $this->get('nova-vendor/nova-stripe/stripe/customers/' . 1)
            ->assertJsonStructure([
                'customer' => [
                    'id',
                    'object',
                    'address',
                    'balance',
                    'created',
                    'currency',
                    'default_source',
                    'delinquent',
                    'description',
                    'discount',
                    'email',
                    'invoice_prefix',
                    'livemode',
                    'metadata',
                    'name',
                    'next_invoice_sequence',
                    'phone',
                    'preferred_locales',
                    'shipping',
                    'tax_exempt',
                ],
            ]);
    }
}
