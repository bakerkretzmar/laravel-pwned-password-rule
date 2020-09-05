<?php

namespace Bakerkretzmar\PwnedPasswordRule;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class PwnedPasswordRuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/pwned-passwored-rule'),
        ], 'pwned-passwored-rule');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang/', 'pwned-passwored-rule');

        Validator::extend('pwned', function ($attribute, $value, $parameters, $validator) {
            return (new PwnedPassword(...$parameters))->passes($attribute, $value);
        });
    }
}
