<?php

namespace Tighten\NovaStripe\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Stripe\Charge;
use Stripe\StripeClient;

class StripeChargeControllerTest extends TestCase
{
    use WithFaker;

    protected $stripe;
    protected $charge;

    public function setUp(): void
    {
        parent::setUp();
        $this->stripe = new StripeClient(Config::get('services.stripe.secret'));

        $this->charge = $this->stripe->charges->all()->count()
            ? $this->stripe->charges->all(['limit' => 1])->first()
            : $this->stripe->charges->create([
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
        $stripeCharge = Charge::retrieve(['id' => $this->charge->id, 'expand' => ['balance_transaction']], ['api_key' => Config::get('services.stripe.secret')]);

        $response->assertJsonFragment([
            'id' => $stripeCharge->id,
            'amount' => $stripeCharge->amount,
            'fee' => $stripeCharge->balance_transaction->fee,
            'net' => $stripeCharge->balance_transaction->net,
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
            ->assertSee($stripeCharge->balance_transaction->fee)
            ->assertSee($stripeCharge->balance_transaction->net)
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
        $balance = $this->stripe->balance->retrieve();

        $this->get('nova-vendor/nova-stripe/stripe/balance')
            ->assertJsonFragment([
                'available' => $balance->available,
                'pending' => $balance->pending,
            ]);
    }
}
