<?php

namespace Tighten\NovaStripe\Clients;

use Exception;
use Stripe\Balance;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Refund;

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

    public function listCustomers($options = [])
    {
        try {
            return Customer::all($options, ['api_key' => $this->apiKey]);
        } catch (Exception $e) {
        }
    }

    public function refundCharge($chargeId)
    {
        try {
            return Refund::create(['charge' => $chargeId], ['api_key' => $this->apiKey]);
        } catch (Exception $e) {
        }
    }

    public function getCustomer($id)
    {
        try {
            return Customer::retrieve($id, ['api_key' => $this->apiKey]);
        } catch (Exception $e) {
        }
    }

    public function createCharge(array $params)
    {
        try {
            return Charge::create($params, ['api_key' => $this->apiKey]);
        } catch (Exception $e) {
        }
    }
}
