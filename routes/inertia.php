<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool Routes
|--------------------------------------------------------------------------
|
| Here is where you may register Inertia routes for your tool. These are
| loaded by the ServiceProvider of the tool. The routes are protected
| by your tool's "Authorize" middleware by default. Now - go build!
|
*/

Route::name('nova-stripe')->group(function () {
    Route::get('/', function () {
        return inertia('Tool');
    })->name('');

    Route::get('charges/{chargeId}', function (string $chargeId) {
        return inertia('Detail', [
            'chargeId' => $chargeId,
        ]);
    })->name('.charge');

    Route::get('customers', function () {
        return inertia('Customers');
    })->name('.customers');

    Route::get('customers/{customerId}', function (string $customerId) {
        return inertia('CustomerDetail', [
            'customerId' => $customerId,
        ]);
    })->name('.customers.show');
});
