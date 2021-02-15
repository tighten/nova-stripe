<?php


namespace Tightenco\NovaStripe\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Stripe\StripeClient;

class StripeChargeControllerTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_can_can_return_a_response()
    {
        $this->get('nova-vendor/nova-stripe/stripe/charges')
            ->assertSuccessful();

        $this->get('nova-vendor/nova-stripe/stripe/balance')
            ->assertSuccessful();

        $stripe = new StripeClient(Config::get('services.stripe.test_secret'));
        $charge = $stripe->charges->create([
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
        $stripe = new StripeClient(Config::get('services.stripe.test_secret'));
        $charge = $stripe->charges->create([
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
        $this->markTestSkipped();
    }

    /** @test */
    public function it_shows_the_current_balance()
    {
        $this->markTestSkipped();
    }
}
