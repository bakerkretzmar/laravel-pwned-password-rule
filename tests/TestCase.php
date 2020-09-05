<?php

namespace Bakerkretzmar\PwnedPasswordRule\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Bakerkretzmar\PwnedPasswordRule\PwnedPasswordRuleServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            PwnedPasswordRuleServiceProvider::class,
        ];
    }
}
