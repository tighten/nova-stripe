<?php

namespace Tighten\NovaStripe\Http;

use Illuminate\Routing\Controller;
use Tighten\NovaStripe\Clients\StripeClient;

class StripeBalanceController extends Controller
{
    public function index()
    {
        $balance = (new StripeClient)->getBalance();
        $values = ['available', 'pending'];
        $currency = config('nova.currency');

        foreach ($values as $value) {
            $balance->{$value} = collect($balance->{$value})->filter(function ($x) use ($currency) {
                return $x['currency'] === strtolower($currency);
            })->toArray();
        }

        return response()->json(['balance' => $balance]);
    }
}
