<?php

namespace Tighten\NovaStripe\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sushi\Sushi;

class Subscription extends BaseModel
{
    use Sushi;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $rows = [];

    protected $service = 'subscriptions';

    protected $expand = ['data.default_payment_method'];

    protected $schema = [
        'id' => 'string',
        'customer_id' => 'string',
        'created' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'currency' => 'string',
        'metadata' => 'json',
        'status' => 'string',
        'cancel_at' => 'datetime',
        'canceled_at' => 'datetime',
        'ended_at' => 'datetime',
        'trial_start' => 'datetime',
        'trial_end' => 'datetime',
        'default_payment_method' => 'json',
        'description' => 'string',
        'items' => 'json',
        'synced_at' => 'datetime',
    ];

    protected $casts = [
        'created' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'cancel_at' => 'datetime',
        'canceled_at' => 'datetime',
        'ended_at' => 'datetime',
        'trial_start' => 'datetime',
        'trial_end' => 'datetime',
        'default_payment_method' => 'json',
        'items' => 'json',
        'synced_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
