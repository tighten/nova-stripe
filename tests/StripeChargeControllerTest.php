<?php


namespace Tightenco\NovaStripe\Tests;

class StripeChargeControllerTest extends TestCase
{
    /** @test */
    public function it_can_can_return_a_response()
    {
        $this
            ->get('nova-vendor/tightenco/nova-stripe/stripe/charges')
            ->assertSuccessful();
    }
}
