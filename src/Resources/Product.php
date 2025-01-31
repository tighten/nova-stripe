<?php

namespace Tighten\NovaStripe\Resources;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Product extends BaseResource
{
    public static $model = \Tighten\NovaStripe\Models\Product::class;

    public static $title = 'name';

    public static $search = [
        'id',
        'name',
    ];

    public static function uriKey()
    {
        return 'stripe-products';
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()
                ->hideFromIndex(),

            Text::make('Name')
                ->sortable(),

            Text::make('Price')
                ->sortable()
                ->displayUsing(function () {
                    $currency = Str::upper($this->default_price['currency']) ?? '';

                    return $currency . ' ' . number_format($this->default_price['unit_amount'] / 100, 2);
                }),

            Text::make('Type')
                ->displayUsing(fn () => Str::of($this->default_price['type'])->replace('_', ' ')->title()),

            Text::make('Recurring Period')
                ->displayUsing(fn () => Str::of(data_get($this->default_price, 'recurring.interval', '-'))->title()),

            Boolean::make('Active')->filterable(),

            Text::make('Details', 'stripeLink')
                ->displayUsing(fn ($value): string => '<a href="' . $value . '" target="_blank" class="link-default">Open in Stripe Dashboard</a>')
                ->asHtml()
                ->hideFromIndex(),

            DateTime::make('Synced At')->hideFromIndex(),
        ];
    }
}
