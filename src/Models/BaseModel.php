<?php

namespace Tighten\NovaStripe\Models;

use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Tighten\NovaStripe\Services\StripeClientService;

abstract class BaseModel extends Model
{
    protected $guarded = [];

    public function sync(): array
    {
        $items = $this->getService()->all([
            'limit' => 100,
            'expand' => $this->expand ?? [],
        ]);

        $records = [];
        foreach ($items->autoPagingIterator() as $item) {
            $records[] = $this->prepareForInsert($item);
        }

        $this->query()->delete();
        $this->insert($records);

        return $records;
    }

    public function prepareForInsert(object $item): array
    {
        $record = [];

        foreach ($this->schema as $key => $value) {
            $fieldValue = $item->{$key} ?? null;

            if ($value === 'json') {
                if (is_object($fieldValue) && method_exists($fieldValue, 'toArray')) {
                    $record[$key] = json_encode($fieldValue->toArray());
                } elseif (is_array($fieldValue)) {
                    $record[$key] = json_encode($fieldValue);
                } else {
                    $record[$key] = $fieldValue;
                }
            } elseif ($value === 'datetime') {
                $record[$key] = $fieldValue ? Carbon::createFromTimestamp($fieldValue)->toDateTimeString() : null;
            } else {
                $record[$key] = $key === 'customer_id' ? $item->customer : $fieldValue;
            }
        }

        $record['synced_at'] = now()->toDateTimeString();

        return $record;
    }

    protected function stripeLink(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes): string => 'https://dashboard.stripe.com/' . $this->service . '/' . $attributes['id'],
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
