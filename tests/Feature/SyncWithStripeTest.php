<?php

use Illuminate\Support\Facades\Notification;
use Laravel\Nova\Notifications\NovaNotification;
use Tighten\NovaStripe\Jobs\SyncWithStripe;
use Tighten\NovaStripe\Models\Charge;
use Tighten\NovaStripe\Models\Customer;
use Tighten\NovaStripe\Models\Product;
use Tighten\NovaStripe\Models\Subscription;
use Tighten\NovaStripe\Tests\TestUser;

beforeEach(function (): void {
    $this->user = TestUser::create([
        'name' => fake()->name(),
        'email' => fake()->email(),
    ]);
});

it('syncs resources and notifies the user', function (): void {
    Notification::fake();

    $mockProduct = Mockery::mock(Product::class);
    $mockProduct->shouldReceive('sync')->once()->andReturn([]);
    app()->instance(Product::class, $mockProduct);

    $mockCustomer = Mockery::mock(Customer::class);
    $mockCustomer->shouldReceive('sync')->once()->andReturn([]);
    app()->instance(Customer::class, $mockCustomer);

    $mockCharge = Mockery::mock(Charge::class);
    $mockCharge->shouldReceive('sync')->once()->andReturn([]);
    app()->instance(Charge::class, $mockCharge);

    $mockSubscription = Mockery::mock(Subscription::class);
    $mockSubscription->shouldReceive('sync')->never();
    app()->instance(Subscription::class, $mockSubscription);

    SyncWithStripe::dispatchSync(['Products' => true, 'Customers' => true, 'Charges' => true, 'Subscriptions' => false], $this->user);

    Notification::assertSentTo(
        notifiable: $this->user,
        notification: NovaNotification::class,
        callback: fn ($notification): bool => str_contains((string) $notification->message, 'sync has completed')
    );
});

it('notifies the user when it fails to sync', function (): void {
    Notification::fake();

    $mockProduct = Mockery::mock(Product::class);
    $mockProduct->shouldReceive('sync')->once()->andThrow(new Exception('Sync failed.'));
    app()->instance(Product::class, $mockProduct);

    try {
        SyncWithStripe::dispatchSync(['Products' => true], $this->user);
    } catch (Exception $e) {
        expect($e->getMessage())->toBe('Sync failed.');
    }

    Notification::assertSentTo(
        notifiable: $this->user,
        notification: NovaNotification::class,
        callback: fn ($notification): bool => str_contains((string) $notification->message, 'The sync process has failed')
    );
});
