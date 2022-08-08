<?php

namespace Tighten\NovaStripe;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaStripe extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('nova-stripe', __DIR__ . '/../dist/js/tool.js');
        Nova::style('nova-stripe', __DIR__ . '/../dist/css/tool.css');
        Nova::translations(__DIR__ . '/../dist/lang/' . app()->getLocale() . '.json');
    }

    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        return MenuSection::make(
            'Nova Stripe',
            [
                MenuItem::make(__('Customers'), '/nova-stripe/customers'),
            ],
            'credit-card'
        )
            ->path('/nova-stripe');
    }
}
