<?php

namespace Bakerkretzmar\PwnedPasswordRule;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PwnedPassword implements Rule
{
    public int $threshold

    public function __construct(int $threshold) {
        $this->threshold = $threshold;
    }

    public function passes($attribute, $value): bool
    {
        // configuration stuff?

        $response = Http::get('https://api.pwnedpasswords.com/range/' . substr($hash = sha1($value), 0, 5));

        $pwned = Arr::first(explode("\r\n", $response->body()), function ($value) use ($hash) {
            return Str::endsWith(strtoupper($hash), explode(':', $value)[0]);
        });

        if (! $pwned) {
            return true;
        }

        $count = explode(':', $pwned)[1];

        return $count <= $this->threshold;
    }

    public function message(): string
    {
        return __('pwned-password-rule::messages.pwned');
    }
}
