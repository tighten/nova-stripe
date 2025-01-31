<?php

namespace Tighten\NovaStripe\Tests;

use Tighten\NovaStripe\ToolServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ToolServiceProvider::class,
        ];
    }
}
