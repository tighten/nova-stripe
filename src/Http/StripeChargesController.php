<?php

namespace Tightenco\NovaStripe\Http;

use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Tightenco\NovaStripe\Clients\StripeClient;

class StripeChargesController extends Controller
{
    public function index()
    {
        $charges = (new StripeClient)->listCharges();

        return response()->json(['charges' => $charges]);
    }
}
