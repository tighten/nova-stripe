<?php

namespace Tighten\NovaStripe\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tighten\NovaStripe\ToolServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ToolServiceProvider::class,
        ];
    }
}
