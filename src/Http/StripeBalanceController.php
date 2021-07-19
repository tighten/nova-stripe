<?php

namespace Tighten\NovaStripe\Http;

use Illuminate\Routing\Controller;
use Tighten\NovaStripe\Clients\StripeClient;

class StripeBalanceController extends Controller
{
    public function index()
    {
        return response()->json(['balance' => (new StripeClient)->getBalance()]);
    }
}
