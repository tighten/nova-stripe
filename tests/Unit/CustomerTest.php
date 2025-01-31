<?php

use Illuminate\Support\Carbon;
use Tighten\NovaStripe\Models\Customer;
use Tighten\NovaStripe\Services\StripeClientService;
use Stripe\Service\CustomerService;

beforeEach(function (): void {
    $this->mockStripeClientService = Mockery::mock(StripeClientService::class);
    $this->mockCustomerService = Mockery::mock(CustomerService::class);

    $mockResponse = Mockery::mock();
    $mockResponse->shouldReceive('autoPagingIterator')->andReturn(collect([
        (object) [
            'id' => 'cus_1',
            'created' => now()->timestamp,
            'name' => 'Mr. Foo',
            'address' => [
                'country' => 'US',
                'city' => 'San Francisco',
            ],
        ],
        (object) [
            'id' => 'cus_2',
            'created' => now()->timestamp,
            'name' => 'Mr. Bar',
            'address' => [
                'country' => 'US',
                'city' => 'Chicago',
            ],
        ],
    ]));

    $this->mockCustomerService->shouldReceive('all')
        ->once()
        ->with(['limit' => 100, 'expand' => ['data.default_source']])
        ->andReturn($mockResponse);

    $this->mockStripeClientService->shouldReceive('getService')
        ->once()
        ->with('customers')
        ->andReturn($this->mockCustomerService);

    app()->instance(StripeClientService::class, $this->mockStripeClientService);

    $this->model = new Customer;
});

it('performs sync operation', function (): void {
    $result = $this->model->sync();

    expect($result)->toHaveCount(2);

    expect($result[0]['id'])->toBe('cus_1');
    expect($result[0]['name'])->toBe('Mr. Foo');
    expect($result[0]['address'])->toBe(json_encode([
        'country' => 'US',
        'city' => 'San Francisco',
    ]));
    expect(Carbon::hasFormat($result[0]['synced_at'], 'Y-m-d H:i:s'))->toBeTrue();

    expect($result[1]['id'])->toBe('cus_2');
    expect($result[1]['name'])->toBe('Mr. Bar');
    expect($result[1]['address'])->toBe(json_encode([
        'country' => 'US',
        'city' => 'Chicago',
    ]));
    expect(Carbon::hasFormat($result[1]['synced_at'], 'Y-m-d H:i:s'))->toBeTrue();
});

it('queries correctly after sync operation', function (): void {
    $this->model->sync();

    $items = $this->model->all();
    expect($items)->toHaveCount(2);

    $item = $this->model->find('cus_1');
    expect($item->name)->toBe('Mr. Foo');
    expect($item->address['country'])->toBe('US');
    expect($item['synced_at'])->toBeInstanceOf(Carbon::class);
});

it('builds correct stripe link attribute', function (): void {
    $this->model->sync();

    $item = $this->model->find('cus_1');
    expect($item->stripe_link)->toBe('https://dashboard.stripe.com/customers/cus_1');
});
