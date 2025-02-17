<?php

namespace Tighten\NovaStripe\Models;

use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Tighten\NovaStripe\Services\StripeClientService;
use Sushi\Sushi;

abstract class BaseModel extends Model
{
    use Sushi;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $rows = [];

    protected $schema = [];

    protected $service = '';

    protected $expand = [];

    protected $guarded = [];

    public function sync(): array
    {
        $items = $this->getService()->all([
            'limit' => 100,
            'expand' => $this->expand ?? [],
        ]);

        $records = collect($items->autoPagingIterator())->map(function ($item) {
            return $this->prepareForInsert($item);
        })->toArray();

        $this->query()->delete();
        $this->insert($records);

        return $records;
    }

    public function prepareForInsert(object $item): array
    {
        $record = [];

        foreach ($this->schema as $column => $type) {
            $fieldValue = $item->{$column} ?? null;

            if ($type === 'json') {
                if (is_object($fieldValue) && method_exists($fieldValue, 'toArray')) {
                    $record[$column] = json_encode($fieldValue->toArray());
                } elseif (is_array($fieldValue)) {
                    $record[$column] = json_encode($fieldValue);
                } else {
                    $record[$column] = $fieldValue;
                }
            } elseif ($type === 'datetime') {
                $record[$column] = $fieldValue ? Carbon::createFromTimestamp($fieldValue)->toDateTimeString() : null;
            } else {
                $record[$column] = $column === 'customer_id' ? $item->customer : $fieldValue;
            }
        }

        $record['synced_at'] = now()->toDateTimeString();

        return $record;
    }

    protected function stripeLink(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes): string => 'https://dashboard.stripe.com/' . ($attributes['livemode'] ? '' : 'test/') . $this->service . '/' . $attributes['id'],
        );
    }

    private function getService()
    {
        if (! property_exists($this, 'service') || empty($this->service)) {
            throw new Exception("The 'service' property must be defined and valid.");
        }

        $client = app(StripeClientService::class);

        return $client->getService($this->service);
    }
}
