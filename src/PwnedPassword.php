<?php

namespace Bakerkretzmar\PwnedPasswordRule;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PwnedPassword implements Rule
{
    public int $threshold;
    public bool $padding;

    public function __construct(int $threshold = 0, bool $padding = true)
    {
        $this->threshold = $threshold;
        $this->padding = $padding;
    }

    public function passes($attribute, $value): bool
    {
        $response = Http::withHeaders(['Add-Padding' => $this->padding ? 'true' : ''])
            ->get('https://api.pwnedpasswords.com/range/' . substr($hash = sha1($value), 0, 5));

        $pwned = Arr::first(explode("\r\n", $response->body()), function ($value) use ($hash) {
            return Str::endsWith(strtoupper($hash), Str::before($value, ':'));
        });

        return $pwned ? (int) Str::after($pwned, ':') <= $this->threshold : true;
    }

    public function message(): string
    {
        return __('pwned-password-rule::messages.pwned');
    }
}
