<?php

namespace Tighten\NovaStripe\Resources;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Customer extends BaseResource
{
    public static $model = \Tighten\NovaStripe\Models\Customer::class;

    public static $title = 'name';

    public static $search = [
        'id',
        'name',
        'email',
    ];

    public static $with = ['charges', 'subscriptions'];

    public static function uriKey()
    {
        return 'stripe-customers';
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()
                ->hideFromIndex(),

            Text::make('Name')
                ->sortable(),

            Email::make('Email')->sortable(),

            DateTime::make('Created')
                ->sortable()
                ->filterable(),

            Text::make('Phone')
                ->hideFromIndex(),

            Text::make('Full Address')
                ->hideFromIndex(),

            Number::make('Balance')
                ->hideFromIndex(),

            Boolean::make('Livemode')
                ->hideFromIndex(),

            Boolean::make('Delinquent')
                ->hideFromIndex(),

            Text::make('Details', 'stripeLink')
                ->displayUsing(fn ($value): string => '<a href="' . $value . '" target="_blank" class="link-default">Open in Stripe Dashboard</a>')
                ->asHtml()
                ->hideFromIndex(),

            DateTime::make('Synced At')->hideFromIndex(),

            HasMany::make('Charges'),

            HasMany::make('Subscriptions'),
        ];
    }
}
