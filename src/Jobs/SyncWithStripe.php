<?php

namespace Tighten\NovaStripe\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laravel\Nova\Notifications\NovaNotification;
use Tighten\NovaStripe\Models\Charge;
use Tighten\NovaStripe\Models\Customer;
use Tighten\NovaStripe\Models\Product;
use Tighten\NovaStripe\Models\Subscription;

class SyncWithStripe implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected $resources, protected $user) {}

    public function handle(): void
    {
        foreach (array_keys(array_filter($this->resources)) as $resource) {
            $model = match ($resource) {
                'Products' => app(Product::class),
                'Customers' => app(Customer::class),
                'Charges' => app(Charge::class),
                'Subscriptions' => app(Subscription::class),
                default => throw new Exception("Model not found for resource: {$resource}"),
            };

            $model->sync();

            $this->user->notify(
                NovaNotification::make()->message("The {$resource} sync has completed")
            );
        }
    }

    public function failed(): void
    {
        $this->user->notify(
            NovaNotification::make()->message('The sync process has failed. Please try again.')
        );
    }
}
