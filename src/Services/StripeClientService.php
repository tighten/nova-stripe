<?php

namespace Tighten\NovaStripe\Services;

use InvalidArgumentException;
use Stripe\Service\ChargeService;
use Stripe\Service\CustomerService;
use Stripe\Service\ProductService;
use Stripe\Service\SubscriptionService;
use Stripe\StripeClient;

class StripeClientService
{
    protected StripeClient $stripeClient;

    public function __construct()
    {
        $this->stripeClient = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Get the appropriate service from the StripeClient.
     *
     * @return ChargeService|CustomerService|ProductService|SubscriptionService
     */
    public function getService(string $service)
    {
        return match ($service) {
            'charges' => $this->stripeClient->charges,
            'customers' => $this->stripeClient->customers,
            'products' => $this->stripeClient->products,
            'subscriptions' => $this->stripeClient->subscriptions,
            default => throw new InvalidArgumentException("Invalid service: {$service}"),
        };
    }
}
