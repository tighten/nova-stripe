<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tighten\NovaStripe\Http\StripeBalanceController;
use Tighten\NovaStripe\Http\StripeChargesController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/stripe/charges', StripeChargesController::class . '@index');
Route::get('/stripe/charges/{id}', StripeChargesController::class . '@show');
Route::get('/stripe/balance', StripeBalanceController::class . '@index');
