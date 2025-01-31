<?php

namespace Tighten\NovaStripe\Resources;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Tighten\NovaStripe\Models\Product;

class Subscription extends BaseResource
{
    public static $model = \Tighten\NovaStripe\Models\Subscription::class;

    public static $title = 'description';

    public static $search = [
        'id',
    ];

    public static $with = ['customer'];

    public static function uriKey()
    {
        return 'stripe-subscriptions';
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->hideFromIndex(),

            BelongsTo::make('Customer')->sortable(),

            Badge::make('Status')->map([
                'active' => 'success',
                'incomplete' => 'warning',
                'incomplete_expired' => 'danger',
                'past_due' => 'warning',
                'canceled' => 'danger',
                'unpaid' => 'danger',
                'trialing' => 'info',
                'paused' => 'info',
            ])->sortable()->filterable(),

            Text::make('Description')
                ->sortable(),

            Text::make('Default Payment Method')->displayUsing(fn ($value) => isset($value['type']) ? Str::title($value['type']) : '-'),

            ...collect([
                'Created',
                'Current Period Start',
                'Current Period End',
            ])->map(fn ($key) => Date::make($key)->filterable()),

            ...collect([
                'Trial Start',
                'Trial End',
                'Cancel At',
                'Canceled At',
                'Ended At',
            ])->map(fn ($key) => Date::make($key)->hideFromIndex()),

            Text::make('Products', fn () => Product::whereIn('id', collect($this->items['data'])->pluck('price.product'))
                ->get()
                ->map(fn ($product): string => '<a class="link-default" href="/nova/resources/stripe-products/' . $product->id . '">' . $product->name . '</a>')
                ->join('<br>'))->asHtml()->hideFromIndex(),

            Text::make('Details', 'stripeLink')
                ->displayUsing(fn ($value): string => '<a href="' . $value . '" target="_blank" class="link-default">Open in Stripe Dashboard</a>')
                ->asHtml()
                ->hideFromIndex(),

            DateTime::make('Synced At')->hideFromIndex(),
        ];
    }
}
