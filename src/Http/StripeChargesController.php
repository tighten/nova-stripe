<?php

namespace Tightenco\NovaStripe\Http;

use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Tightenco\NovaStripe\Clients\StripeClient;

class StripeChargesController extends Controller
{
    public function index()
    {
        $charges = (new StripeClient)->listCharges(
            collect([
                'created' => request()->created,
                'customer' => request()->customer,
                'ending_before' => request()->customer,
                'limit' => request()->limit,
                'source' => request()->source,
                'starting_after' => request()->starting_after,
                'transfer_group' => request()->transfer_group
            ])->reject(function($name) {
                return empty($name);
            })->toArray()
        );

        return response()->json(['charges' => $charges]);
    }
}
