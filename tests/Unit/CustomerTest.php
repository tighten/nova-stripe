<?php

use Illuminate\Support\Carbon;
use Stripe\Service\CustomerService;
use Tighten\NovaStripe\Models\Customer;
use Tighten\NovaStripe\Services\StripeClientService;

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
                'line1' => '123 Fake St.',
                'country' => 'US',
                'city' => 'San Francisco',
                'postal_code' => 60007
            ],
            'livemode' => true,
        ],
        (object) [
            'id' => 'cus_2',
            'created' => now()->timestamp,
            'name' => 'Mr. Bar',
            'address' => [
                'line1' => ' 456 Fake St. ',
                'country' => 'US',
                'city' => 'Chicago',
                'postal_code' => 94016
            ],
            'livemode' => false,
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
        'line1' => '123 Fake St.',
        'country' => 'US',
        'city' => 'San Francisco',
        'postal_code' => 60007,
    ]));
    expect(Carbon::hasFormat($result[0]['synced_at'], 'Y-m-d H:i:s'))->toBeTrue();

    expect($result[1]['id'])->toBe('cus_2');
    expect($result[1]['name'])->toBe('Mr. Bar');
    expect($result[1]['address'])->toBe(json_encode([
        'line1' => ' 456 Fake St. ',
        'country' => 'US',
        'city' => 'Chicago',
        'postal_code' => 94016,
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

    $item = $this->model->find('cus_2');
    expect($item->stripe_link)->toBe('https://dashboard.stripe.com/test/customers/cus_2');
});

it('builds correct full address attribute', function (): void {
    $this->model->sync();

    $item = $this->model->find('cus_1');
    expect($item->full_address)->toBe('123 Fake St. San Francisco, US 60007');

    $item = $this->model->find('cus_2');
    expect($item->full_address)->toBe('456 Fake St. Chicago, US 94016');
});
