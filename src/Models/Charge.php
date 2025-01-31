<?php

namespace Tighten\NovaStripe\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sushi\Sushi;

class Charge extends BaseModel
{
    use Sushi;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $rows = [];

    protected $service = 'charges';

    protected $schema = [
        'id' => 'string',
        'object' => 'string',
        'amount' => 'integer',
        'amount_captured' => 'integer',
        'amount_refunded' => 'integer',
        'application' => 'string',
        'application_fee' => 'string',
        'application_fee_amount' => 'integer',
        'balance_transaction' => 'string',
        'billing_details' => 'json',
        'calculated_statement_descriptor' => 'string',
        'captured' => 'boolean',
        'created' => 'datetime',
        'currency' => 'string',
        'customer_id' => 'string',
        'description' => 'string',
        'destination' => 'string',
        'dispute' => 'string',
        'disputed' => 'boolean',
        'failure_balance_transaction' => 'string',
        'failure_code' => 'string',
        'failure_message' => 'string',
        'fraud_details' => 'json',
        'invoice' => 'string',
        'livemode' => 'boolean',
        'metadata' => 'json',
        'on_behalf_of' => 'string',
        'order' => 'string',
        'outcome' => 'json',
        'paid' => 'boolean',
        'payment_intent' => 'string',
        'payment_method' => 'string',
        'payment_method_details' => 'json',
        'receipt_email' => 'string',
        'receipt_number' => 'string',
        'receipt_url' => 'string',
        'refunded' => 'boolean',
        'review' => 'string',
        'shipping' => 'json',
        'source' => 'json',
        'source_transfer' => 'string',
        'statement_descriptor' => 'string',
        'statement_descriptor_suffix' => 'string',
        'status' => 'string',
        'transfer_data' => 'json',
        'transfer_group' => 'string',
        'synced_at' => 'datetime',
    ];

    protected $casts = [
        'billing_details' => 'json',
        'fraud_details' => 'json',
        'metadata' => 'json',
        'outcome' => 'json',
        'payment_method_details' => 'json',
        'shipping' => 'json',
        'source' => 'json',
        'transfer_data' => 'json',
        'created' => 'datetime',
        'synced_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    protected function stripeLink(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes): string => 'https://dashboard.stripe.com/payments/' . $attributes['payment_intent'],
        );
    }
}
