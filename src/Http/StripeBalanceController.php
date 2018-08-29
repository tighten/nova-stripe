<?php

namespace Tightenco\NovaStripe\Http;

use Illuminate\Routing\Controller;
use Tightenco\NovaStripe\Clients\StripeClient;

class StripeBalanceController extends Controller
{
    public function index()
    {
        return response()->json(['balance' => (new StripeClient)->getBalance()]);
    }
}
