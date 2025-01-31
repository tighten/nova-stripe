<?php

namespace Tighten\NovaStripe\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Sushi\Sushi;

class Customer extends BaseModel
{
    use Sushi;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $rows = [];

    protected $service = 'customers';

    protected $expand = ['data.default_source'];

    protected $schema = [
        'id' => 'string',
        'name' => 'string',
        'email' => 'string',
        'address' => 'json',
        'phone' => 'string',
        'balance' => 'integer',
        'livemode' => 'boolean',
        'delinquent' => 'boolean',
        'default_source' => 'json',
        'created' => 'datetime',
        'synced_at' => 'datetime',
    ];

    protected $casts = [
        'default_source' => 'json',
        'address' => 'json',
        'created' => 'datetime',
        'synced_at' => 'datetime',
    ];

    public function charges()
    {
        return $this->hasMany(Charge::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: function ($value, array $attributes) {
                $address = json_decode((string) $attributes['address'], true);

                return trim(
                    $address['line1'] . ' ' .
                    ($address['line2'] ? $address['line2'] . ' ' : '') .
                    $address['city'] . ', ' .
                    $address['country'] . ' ' .
                    $address['postal_code']
                );
            }
        );
    }
}
