<?php

use Illuminate\Support\Carbon;
use Stripe\Service\ChargeService;
use Tighten\NovaStripe\Models\Charge;
use Tighten\NovaStripe\Services\StripeClientService;

beforeEach(function (): void {
    $this->mockStripeClientService = Mockery::mock(StripeClientService::class);
    $this->mockChargeService = Mockery::mock(ChargeService::class);

    $mockResponse = Mockery::mock();
    $mockResponse->shouldReceive('autoPagingIterator')->andReturn(collect([
        (object) [
            'id' => 'ch_1',
            'object' => 'charge',
            'amount' => 1000,
            'currency' => 'usd',
            'paid' => true,
            'status' => 'succeeded',
            'created' => now()->timestamp,
            'customer' => 'cus_123',
            'payment_intent' => 'pi_123',
            'transfer_data' => ['foo' => 'bar'],
        ],
        (object) [
            'id' => 'ch_2',
            'object' => 'charge',
            'amount' => 500,
            'currency' => 'usd',
            'paid' => true,
            'status' => 'succeeded',
            'created' => now()->timestamp,
            'customer' => 'cus_456',
            'payment_intent' => 'pi_456',
            'transfer_data' => ['foo' => 'bar'],
        ],
    ]));

    $this->mockChargeService->shouldReceive('all')
        ->once()
        ->with(['limit' => 100, 'expand' => []])
        ->andReturn($mockResponse);

    $this->mockStripeClientService->shouldReceive('getService')
        ->once()
        ->with('charges')
        ->andReturn($this->mockChargeService);

    app()->instance(StripeClientService::class, $this->mockStripeClientService);

    $this->model = new Charge;
});

it('performs sync operation', function (): void {
    $result = $this->model->sync();

    expect($result)->toHaveCount(2);

    expect($result[0]['id'])->toBe('ch_1');
    expect($result[0]['amount'])->toBe(1000);
    expect($result[0]['customer_id'])->toBe('cus_123');
    expect($result[0]['transfer_data'])->toBe(json_encode(['foo' => 'bar']));
    expect(Carbon::hasFormat($result[0]['synced_at'], 'Y-m-d H:i:s'))->toBeTrue();

    expect($result[1]['id'])->toBe('ch_2');
    expect($result[1]['amount'])->toBe(500);
    expect($result[1]['customer_id'])->toBe('cus_456');
    expect($result[1]['transfer_data'])->toBe(json_encode(['foo' => 'bar']));
    expect(Carbon::hasFormat($result[1]['synced_at'], 'Y-m-d H:i:s'))->toBeTrue();
});

it('queries correctly after sync operation', function (): void {
    $this->model->sync();

    $items = $this->model->all();
    expect($items)->toHaveCount(2);

    $item = $this->model->where('amount', 500)->first();
    expect($item->id)->toBe('ch_2');
    expect($item->transfer_data)->toBe(['foo' => 'bar']);
    expect($item['synced_at'])->toBeInstanceOf(Carbon::class);
});

it('builds correct stripe link attribute', function (): void {
    $this->model->sync();

    $item = $this->model->where('amount', 500)->first();
    expect($item->stripe_link)->toBe('https://dashboard.stripe.com/payments/pi_456');
});
