<?php

namespace Tighten\NovaStripe\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Tighten\NovaStripe\Clients\StripeClient;

class StripeChargeControllerTest extends TestCase
{
    use WithFaker;

    protected $stripe;
    protected $charge;

    public function setUp(): void
    {
        parent::setUp();
        $this->stripe = new StripeClient;

        $this->charge = $this->findSuccessfulNonRefundedCharge()
            ?: $this->stripe->createCharge([
                'amount' => $this->faker->numberBetween(50, 1000),
                'currency' => 'usd',
                'source' => 'tok_mastercard',
                'description' => $this->faker->sentence,
            ]);
    }

    /** @test */
    public function it_can_can_return_a_response()
    {
        $this->get('nova-vendor/nova-stripe/stripe/charges')
            ->assertSuccessful();

        $this->get('nova-vendor/nova-stripe/stripe/balance')
            ->assertSuccessful();

        $this->get('nova-vendor/nova-stripe/stripe/charges/' . $this->charge->id)
            ->assertSuccessful();
    }

    /** @test */
    public function it_returns_a_list_of_charges()
    {
        $this->get('nova-vendor/nova-stripe/stripe/charges')
            ->assertJsonFragment(['description' => $this->charge->description])
            ->assertJsonFragment(['amount' => $this->charge->amount]);
    }

    /** @test */
    public function it_returns_charge_details()
    {
        $response = $this->get('nova-vendor/nova-stripe/stripe/charges/' . $this->charge->id);
        $stripeCharge = $this->stripe->getCharge($this->charge->id);

        $response->assertJsonFragment([
            'id' => $stripeCharge->id,
            'amount' => $stripeCharge->amount,
            'status' => $stripeCharge->status,
            'created' => $stripeCharge->created,
            'metadata' => $stripeCharge->metadata,
            'livemode' => $stripeCharge->livemode,
            'captured' => $stripeCharge->captured,
            'paid' => $stripeCharge->paid,
            'refunded' => $stripeCharge->refunded,
            'dispute' => $stripeCharge->dispute,
            'fraud_details' => $stripeCharge->fraud_details,
            'transfer_group' => $stripeCharge->transfer_group,
        ]);

        $response->assertSee($stripeCharge->id)
            ->assertSee($stripeCharge->amount)
            ->assertSee($stripeCharge->status)
            ->assertSee($stripeCharge->created)
            ->assertSee($stripeCharge->livemode)
            ->assertSee($stripeCharge->captured)
            ->assertSee($stripeCharge->paid)
            ->assertSee($stripeCharge->refunded)
            ->assertSee($stripeCharge->dispute)
            ->assertSee($stripeCharge->transfer_group);
    }

    /** @test */
    public function it_shows_the_current_balance()
    {
        $balance = $this->stripe->getBalance();

        $this->get('nova-vendor/nova-stripe/stripe/balance')
            ->assertJsonFragment([
                'available' => $balance->available,
                'pending' => $balance->pending,
            ]);
    }

    /** @test */
    public function it_can_refund_a_charge_successfully()
    {
        $this->post('nova-vendor/nova-stripe/stripe/charges/' . $this->charge->id . '/refund')
            ->assertSuccessful()
            ->assertJsonFragment([
                'charge' => $this->charge->id,
                'status' => 'succeeded',
            ]);

        $this->assertTrue($this->stripe->getCharge($this->charge->id)->refunded);
    }

    public function findSuccessfulNonRefundedCharge()
    {
        $charges = $this->stripe->listCharges();

        foreach ($charges as $charge) {
            if (! $charge->refunded && $charge->status === 'succeeded') {
                return $charge;
            }
        }

        return null;
    }
}
