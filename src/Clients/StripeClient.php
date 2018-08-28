<?php

namespace Tightenco\NovaStripe\Clients;

use Exception;
use Stripe\Charge;

class StripeClient
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.stripe.secret');
    }

    public function listCharges($options = [])
    {
        try {
            return Charge::all($options, ['api_key' => $this->apiKey]);
        } catch(Exception $e) {

        }
    }
}
