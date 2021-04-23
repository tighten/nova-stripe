<?php

namespace Tighten\NovaStripe\Http;

use Illuminate\Routing\Controller;
use Tighten\NovaStripe\Clients\StripeClient;

class StripeChargesController extends Controller
{
    public function index()
    {
        $charges = (new StripeClient)->listCharges(
            request()->only('created', 'customer', 'ending_before', 'limit', 'source', 'starting_after', 'transfer_group')
        );

        return response()->json(['charges' => $charges]);
    }

    public function show($id)
    {
        return response()->json(['charge' => (new StripeClient)->getCharge($id)]);
    }
}
