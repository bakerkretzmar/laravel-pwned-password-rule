Laravel Pwned Password Rule
===========================

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bakerkretzmar/laravel-pwned-password-rule.svg?style=flat&label=Packagist)](https://packagist.org/packages/bakerkretzmar/laravel-pwned-password-rule)
[![Total Downloads](https://img.shields.io/packagist/dt/bakerkretzmar/laravel-pwned-password-rule.svg?style=flat&label=Downloads)](https://packagist.org/packages/bakerkretzmar/laravel-pwned-password-rule)
[![Build Status](https://github.com/bakerkretzmar/laravel-pwned-password-rule/workflows/CI/badge.svg)](https://github.com/bakerkretzmar/laravel-pwned-password-rule/actions)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg?style=flat)](https://opensource.org/licenses/MIT)

Validate passwords against the **Have I Been Pwned** database.

[Have I Been Pwned](https://haveibeenpwned.com/) is a service that lets you check if any of your accounts have been compromised in a data breach. In addition to their website and account search functionality, they operate a Pwned Passwords tool that allows securely and anonymously searching just for _passwords_ found in breaches. Under the hood, this validation rule queries the Pwned Passwords API and checks _if_ and _how often_ the value being validated appears in HIBP's breach database.

**This package does NOT share your users’ passwords with third parties.** Values being validated using this rule are hashed in your application, and the first five characters of the hash are sent to the Pwned Password API. The API returns all password hash suffixes matching these five characters, and back in your application this rule determines which hash matches the value you sent. This package also supports [response padding](https://haveibeenpwned.com/API/v3#PwnedPasswordsPadding) to further obscure the API's responses to hash queries.

For more information please read the launch announcement of Pwned Passwords, [Introducing 306 Million Freely Downloadable Pwned Passwords](https://www.troyhunt.com/introducing-306-million-freely-downloadable-pwned-passwords/), the V2 announcement, [I've Just Launched "Pwned Passwords" V2 With Half a Billion Passwords for Download](https://www.troyhunt.com/ive-just-launched-pwned-passwords-version-2/), and the [Have I Been Pwned API documentation](https://haveibeenpwned.com/API/v3).

## Installation

You can install the package with Composer:

```bash
composer require bakerkretzmar/laravel-pwned-password-rule
```

## Usage

Use this rule like any other Laravel validation rule:

```php
use Bakerkretzmar\PwnedPasswordRuled\Pwned;

$request->validate([
    'email' => ['required', 'email'],
    'password' => ['required', 'confirmed', 'min:12', new Pwned],
]);
```

You can also use the rule's string alias:

```php
$request->validate(['password' => ['required', 'pwned']]);
```

By default, the rule will fail any value that has _ever_ appeared in Have I Been Pwned's breach database, which contains over 500,000,000 passwords. To allow passwords that have been breached but don't appear in the database often, you can pass an integer to the rule as its first argument. Values appearing that many times or fewer will then pass validation.

```php
// Fails for 'password', passes for 'alpaca999' which appears 3 times

$request->validate(['password' => ['required', new Pwned(5)]]);
// or
$request->validate(['password' => ['required', 'pwned:5']]);
```

Pwned Passwords also offers additional security with optional response padding, which pads responses with fake hashes to a length of 800–1,000 lines, to defend against attacks that inspect the _size_ of the response to determine how many matches the API returned. You can enable response padding by passing `true` as the second argument to this rule.

```php
// Under the hood, returns at least 800 password hashes regardless of how many matched the query

$request->validate(['password' => ['required', new Pwned(0, true)]]);
// or
$request->validate(['password' => ['required', 'pwned:0,true']]);
```

## Security

If you find any security related issues with this package, please email <jacobtbk@gmail.com> instead of submitting an issue.

## Credits

- [Troy Hunt](https://twitter.com/troyhunt) created and maintains Have I Been Pwned

## License

This package is release under the MIT License. See [LICENSE.md](LICENSE.md).
