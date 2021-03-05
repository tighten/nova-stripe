<?php


namespace Tightenco\NovaStripe\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Stripe\Charge;
use Stripe\StripeClient;

class StripeChargeControllerTest extends TestCase
{
    use WithFaker;

    protected $stripe;

    public function setUp(): void
    {
        parent::setUp();
        $this->stripe = new StripeClient(Config::get('services.stripe.secret'));
    }

    /** @test */
    public function it_can_can_return_a_response()
    {
        $this->get('nova-vendor/nova-stripe/stripe/charges')
            ->assertSuccessful();

        $this->get('nova-vendor/nova-stripe/stripe/balance')
            ->assertSuccessful();

        $charge = $this->stripe->charges->create([
            'amount' => $this->faker->numberBetween(50, 1000),
            'currency' => 'usd',
            'source' => 'tok_mastercard',
            'description' => $this->faker->sentence,
        ]);

        $this->get('nova-vendor/nova-stripe/stripe/charges/' . $charge->id)
            ->assertSuccessful();
    }

    /** @test */
    public function it_returns_a_list_of_charges()
    {
        $charge = $this->stripe->charges->create([
            'amount' => $this->faker->numberBetween(50, 1000),
            'currency' => 'usd',
            'source' => 'tok_mastercard',
            'description' => $this->faker->sentence,
        ]);

        $this->get('nova-vendor/nova-stripe/stripe/charges')
            ->assertJsonFragment(['description' => $charge->description])
            ->assertJsonFragment(['amount' => $charge->amount]);
    }

    /** @test */
    public function it_returns_charge_details()
    {
        $charge = $this->stripe->charges->create([
            'amount' => 51,
            'currency' => 'usd',
            'source' => 'tok_mastercard',
            'description' => 'charge details test description',
        ]);

        $response = $this->get('nova-vendor/nova-stripe/stripe/charges/' . $charge->id);
        $stripeCharge = Charge::retrieve(['id' => $charge->id, 'expand' => ['balance_transaction']], ['api_key' => Config::get('services.stripe.secret')]);

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
