<?php

namespace Tighten\NovaStripe;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Tighten\NovaStripe\Resources\Charge;
use Tighten\NovaStripe\Resources\Customer;
use Tighten\NovaStripe\Resources\Product;
use Tighten\NovaStripe\Resources\Subscription;

class NovaStripe extends Tool
{
    public function boot(): void
    {
        Nova::script('nova-stripe', __DIR__ . '/../resources/js/index.js');

        Nova::resources([
            Customer::class,
            Product::class,
            Charge::class,
            Subscription::class,
        ]);
    }

    public function menu(Request $request): MenuSection
    {
        return MenuSection::make('Stripe', [
            MenuItem::make('Products', '/resources/stripe-products'),
            MenuItem::make('Customers', '/resources/stripe-customers'),
            MenuItem::make('Charges', '/resources/stripe-charges'),
            MenuItem::make('Subscriptions', '/resources/stripe-subscriptions'),
        ], 'credit-card');
    }
}
