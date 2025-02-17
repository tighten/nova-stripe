<?php

namespace Tighten\NovaStripe\Models;

class Product extends BaseModel
{
    protected $service = 'products';

    protected $expand = ['data.default_price'];

    protected $schema = [
        'id' => 'string',
        'name' => 'string',
        'currency' => 'string',
        'active' => 'boolean',
        'livemode' => 'boolean',
        'default_price' => 'json',
        'recurring' => 'json',
        'synced_at' => 'datetime',
    ];

    protected $casts = [
        'active' => 'boolean',
        'default_price' => 'json',
        'recurring' => 'json',
        'synced_at' => 'datetime',
        'livemode' => 'boolean',
    ];
}
