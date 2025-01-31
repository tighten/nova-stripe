<?php

use Illuminate\Support\Carbon;
use Tighten\NovaStripe\Models\Subscription;
use Tighten\NovaStripe\Services\StripeClientService;
use Stripe\Service\SubscriptionService;

beforeEach(function (): void {
    $this->mockStripeClientService = Mockery::mock(StripeClientService::class);
    $this->mockSubscriptionService = Mockery::mock(SubscriptionService::class);

    $mockResponse = Mockery::mock();
    $mockResponse->shouldReceive('autoPagingIterator')->andReturn(collect([
        (object) [
            'id' => 'sub_1',
            'created' => now()->timestamp,
            'currency' => 'usd',
            'customer' => 'cus_123',
            'items' => [
                'data' => [
                    ['price' => ['product' => 'prod_123']],
                ],
            ],
        ],
        (object) [
            'id' => 'sub_2',
            'created' => now()->timestamp,
            'currency' => 'usd',
            'customer' => 'cus_456',
            'items' => [
                'data' => [
                    ['price' => ['product' => 'prod_456']],
                ],
            ],
        ],
    ]));

    $this->mockSubscriptionService->shouldReceive('all')
        ->once()
        ->with(['limit' => 100, 'expand' => ['data.default_payment_method']])
        ->andReturn($mockResponse);

    $this->mockStripeClientService->shouldReceive('getService')
        ->once()
        ->with('subscriptions')
        ->andReturn($this->mockSubscriptionService);

    app()->instance(StripeClientService::class, $this->mockStripeClientService);

    $this->model = new Subscription;
});

it('performs sync operation', function (): void {
    $result = $this->model->sync();

    expect($result)->toHaveCount(2);

    expect($result[0]['id'])->toBe('sub_1');
    expect($result[0]['currency'])->toBe('usd');
    expect($result[0]['items'])->toBe(json_encode([
        'data' => [
            ['price' => ['product' => 'prod_123']],
        ],
    ]));
    expect(Carbon::hasFormat($result[0]['synced_at'], 'Y-m-d H:i:s'))->toBeTrue();

    expect($result[1]['id'])->toBe('sub_2');
    expect($result[1]['currency'])->toBe('usd');
    expect($result[1]['items'])->toBe(json_encode([
        'data' => [
            ['price' => ['product' => 'prod_456']],
        ],
    ]));
    expect(Carbon::hasFormat($result[1]['synced_at'], 'Y-m-d H:i:s'))->toBeTrue();
});

it('queries correctly after sync operation', function (): void {
    $this->model->sync();

    $items = $this->model->all();
    expect($items)->toHaveCount(2);

    $item = $this->model->find('sub_1');
    expect($item->currency)->toBe('usd');
    expect($item->items['data'][0]['price']['product'])->toBe('prod_123');
    expect($item['synced_at'])->toBeInstanceOf(Carbon::class);
});

it('builds correct stripe link attribute', function (): void {
    $this->model->sync();

    $item = $this->model->find('sub_1');
    expect($item->stripe_link)->toBe('https://dashboard.stripe.com/subscriptions/sub_1');
});
