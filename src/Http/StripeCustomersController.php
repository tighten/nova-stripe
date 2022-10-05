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

    public function show($id)
    {
        $customer = (new StripeClient)->getCustomer($id);

        return response()->json(['customer' => $customer]);
    }
}
