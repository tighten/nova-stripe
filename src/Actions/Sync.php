<?php

namespace Tighten\NovaStripe\Actions;

use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Http\Requests\NovaRequest;
use Tighten\NovaStripe\Jobs\SyncWithStripe;

class Sync extends Action
{
    public $name = 'Sync With Stripe';

    public $confirmText;

    public $confirmButtonText = 'Sync With Stripe';

    protected $user;

    public function __construct()
    {
        $this->user = request()->user();
    }

    public function handle(ActionFields $fields)
    {
        if ($fields->queue) {
            SyncWithStripe::dispatch($fields->resources, $this->user);
        } else {
            SyncWithStripe::dispatchSync($fields->resources, $this->user);
        }

        return ActionResponse::message($fields->queue ? 'Sync started!' : 'Sync completed!');
    }

    public function fields(NovaRequest $request)
    {
        return [
            BooleanGroup::make('Resources', 'resources')
                ->options([
                    'Products',
                    'Customers',
                    'Charges',
                    'Subscriptions',
                ])
                ->help('Check all the resources you wish to sync with Stripe.')
                ->rules('required'),

            Boolean::make('Run in the background', 'queue')
                ->help('Check this if you want the sync to run in the background (recommended for large datasets, e.g. 1,000+ records or more). You will receive a notification when the sync is complete.')
                ->default(true),
        ];
    }
}
