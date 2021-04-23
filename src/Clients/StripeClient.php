<?php

namespace Tighten\NovaStripe\Clients;

use Exception;
use Stripe\Balance;
use Stripe\Charge;

class StripeClient
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.stripe.secret');
    }

    /**
     * @param array $options - optional list of params (https://stripe.com/docs/api/php#list_charges)
     *      ['created']         array - a filter based on the "created" field
     *      ['customer']        string - only return charges for this customer ID
     *      ['ending_before']   string - a cursor for use in pagination. Object ID.
     *      ['limit']           integer - number of objects to be returned (1-100)
     *      ['source']          array - a filter on the list based on the soure of the charge
     *      ['starting_after']  string - a cursor for use in pagination. Object ID.
     *      ['transfer_group']  string - only return charges for this transfer group
     * @return \Stripe\Collection
     */
    public function listCharges($options = [])
    {
        try {
            return Charge::all($options, ['api_key' => $this->apiKey]);
        } catch (Exception $e) {

        }
    }

    public function getCharge($id)
    {
        try {
            return Charge::retrieve(['id' => $id, 'expand' => ['balance_transaction']], ['api_key' => $this->apiKey]);
        } catch (Exception $e) {

        }
    }

    public function getBalance()
    {
        try {
            return Balance::retrieve(['api_key' => $this->apiKey]);
        } catch (Exception $e) {

        }
    }
}
