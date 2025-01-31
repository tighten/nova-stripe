<?php

namespace Tighten\NovaStripe\Models;

use Sushi\Sushi;

class Product extends BaseModel
{
    use Sushi;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $rows = [];

    protected $service = 'products';

    protected $expand = ['data.default_price'];

    protected $schema = [
        'id' => 'string',
        'name' => 'string',
        'currency' => 'string',
        'active' => 'boolean',
        'default_price' => 'json',
        'recurring' => 'json',
        'synced_at' => 'datetime',
    ];

    protected $casts = [
        'active' => 'boolean',
        'default_price' => 'json',
        'recurring' => 'json',
        'synced_at' => 'datetime',
    ];
}
