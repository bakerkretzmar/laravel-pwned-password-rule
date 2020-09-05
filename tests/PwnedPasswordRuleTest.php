<?php

namespace Bakerkretzmar\PwnedPasswordRule\Tests;

use Bakerkretzmar\PwnedPasswordRule\Pwned;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PwnedPasswordRuleTest extends TestCase
{
    /** @test */
    public function can_check_if_password_has_been_pwned()
    {
        $this->assertFalse(validator(['password' => 'password'], ['password' => new Pwned])->passes());
        $this->assertTrue(validator(['password' => Str::random(40)], ['password' => new Pwned])->passes());
    }

    /** @test */
    public function can_use_rule_string_alias()
    {
        $this->assertFalse(validator(['password' => 'password'], ['password' => 'pwned'])->passes());
        $this->assertTrue(validator(['password' => Str::random(40)], ['password' => 'pwned'])->passes());
    }

    /** @test */
    public function can_check_password_pwnage_under_threshold()
    {
        // 'password400' pwned 68 times as of September 5, 2020
        $this->assertTrue(validator(['password' => 'password400'], ['password' => new Pwned(68)])->passes());
        $this->assertFalse(validator(['password' => 'password400'], ['password' => new Pwned(67)])->passes());
    }

    /** @test */
    public function can_enable_response_padding()
    {
        Http::fake();

        validator(['password' => 'password400'], ['password' => new Pwned(68, true)])->passes();

        Http::assertSent(function ($request) {
            $this->assertTrue($request->hasHeader('Add-Padding', 'true'));
            return true;
        });
    }
}
