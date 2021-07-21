<?php

namespace Tighten\NovaStripe\Http;

use Illuminate\Routing\Controller;
use Tighten\NovaStripe\Clients\StripeClient;

class StripeCustomersController extends Controller
{
    public function index()
    {
        return response()->json(['customers' => (new StripeClient)->listCustomers()]);
    }
}
