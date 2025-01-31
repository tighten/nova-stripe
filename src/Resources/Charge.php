<?php

namespace Tighten\NovaStripe\Resources;

use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Charge extends BaseResource
{
    public static $model = \Tighten\NovaStripe\Models\Charge::class;

    public static $title = 'id';

    public static $search = [
        'id',
        'amount',
    ];

    public static $with = ['customer'];

    public static function uriKey()
    {
        return 'stripe-charges';
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->hideFromIndex(),

            BelongsTo::make('Customer')->sortable(),

            Number::make('Amount')
                ->sortable()
                ->displayUsing(fn ($value) => $this->formatAmount($value)),

            Badge::make('Status')->map([
                'canceled' => 'danger',
                'succeeded' => 'success',
                'pending' => 'warning',
                'failed' => 'danger',
                'requires_capture' => 'info',
            ])->sortable()->filterable(),

            Boolean::make('Paid')->sortable()->filterable(),

            Text::make('Payment Method')->displayUsing(function ($value) {
                $details = $this->payment_method_details;

                if (isset($details['type']) && $details['type'] === 'card') {
                    $card = $details['card'] ?? [];
                    $brand = strtoupper($card['brand'] ?? 'CARD');
                    $last4 = $card['last4'] ?? '••••';

                    return "{$brand} •••• {$last4}";
                }

                return strtoupper($details['type'] ?? 'UNKNOWN');
            })->sortable(),

            Text::make('Description')->sortable(),

            DateTime::make('Created')->sortable()->filterable(),

            Text::make('Payment', 'stripeLink')
                ->displayUsing(fn ($value): string => '<a href="' . $value . '" target="_blank" class="link-default">Open in Stripe Dashboard</a>')
                ->asHtml()
                ->hideFromIndex(),

            KeyValue::make('Metadata')->rules('json'),

            DateTime::make('Synced At')->hideFromIndex(),

            Panel::make('Receipt and Invoice', $this->receiptAndInvoiceFields()),

            Panel::make('Disputes and Refunds', $this->disputeAndRefundFields()),

            Panel::make('Application Details', $this->applicationFields()),

            Panel::make('Additional Details', $this->additionalFields()),
        ];
    }

    protected function receiptAndInvoiceFields(): array
    {
        return [
            Text::make('Receipt', 'receipt_url')
                ->displayUsing(fn ($value): string => '<a href="' . $value . '" target="_blank" class="link-default">Open Receipt</a>')
                ->asHtml()
                ->hideFromIndex(),

            Text::make('Receipt Email')->hideFromIndex(),

            Text::make('Receipt Number')->hideFromIndex(),

            Text::make('Invoice ID', 'invoice')->hideFromIndex(),

            KeyValue::make('Billing Details')->rules('json'),
        ];
    }

    protected function disputeAndRefundFields(): array
    {
        return [
            Boolean::make('Disputed')->hideFromIndex(),

            Text::make('Dispute')->hideFromIndex(),

            Boolean::make('Refunded')->hideFromIndex(),

            Number::make('Amount Refunded')
                ->displayUsing(fn ($value) => $this->formatAmount($value))
                ->hideFromIndex(),
        ];
    }

    protected function applicationFields(): array
    {
        return [
            Text::make('Application ID', 'application')->hideFromIndex(),

            Text::make('Application Fee ID', 'application_fee')->hideFromIndex(),

            Number::make('Application Fee Amount')
                ->displayUsing(fn ($value) => $this->formatAmount($value))
                ->hideFromIndex(),
        ];
    }

    protected function additionalFields(): array
    {
        return [
            Text::make('Balance Transaction ID', 'balance_transaction')
                ->hideFromIndex(),

            Text::make('Calculated Statement Descriptor')->hideFromIndex(),

            Text::make('Destination')->hideFromIndex(),

            Text::make('Failure Balance Transaction')->hideFromIndex(),

            Text::make('Failure Code')->hideFromIndex(),

            Text::make('Failure Message')->hideFromIndex(),

            Boolean::make('Live Mode')->hideFromIndex(),

            Text::make('On Behalf Of')->hideFromIndex(),

            Text::make('Order')->hideFromIndex(),

            Text::make('Review')->hideFromIndex(),

            Text::make('Source Transfer')->hideFromIndex(),

            Text::make('Statement Descriptor')->hideFromIndex(),

            Text::make('Statement Descriptor Suffix')->hideFromIndex(),

            Text::make('Transfer Group')->hideFromIndex(),

            KeyValue::make('Shipping')
                ->rules('json'),

            KeyValue::make('Fraud Details')
                ->rules('json'),

            KeyValue::make('Source')
                ->rules('json'),

            KeyValue::make('Transfer Data')
                ->rules('json'),

            KeyValue::make('Outcome')
                ->rules('json'),
        ];
    }
}
