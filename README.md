# a lightweight implementation of generating OTP and validating backed by Cache Storage for Laravel

When you use Redis or any Cache driver it will self destruct things

[![Latest Version on Packagist](https://img.shields.io/packagist/v/clarity-tech/laravel-otp-generator.svg?style=flat-square)](https://packagist.org/packages/clarity-tech/laravel-otp-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/clarity-tech/laravel-otp-generator/run-tests?label=tests)](https://github.com/clarity-tech/laravel-otp-generator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/clarity-tech/laravel-otp-generator/Check%20&%20fix%20styling?label=code%20style)](https://github.com/clarity-tech/laravel-otp-generator/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/clarity-tech/laravel-otp-generator.svg?style=flat-square)](https://packagist.org/packages/clarity-tech/laravel-otp-generator)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

We invest a lot of resources into creating [best in class open source packages](https://claritytech.io). You can support us by [buying one of our paid products](https://claritytech.io).

## Installation

You can install the package via composer:

```bash
composer require clarity-tech/laravel-otp-generator
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-otp-generator-config"
```

This is the contents of the published config file:

```php

// config for ClarityTech/LaravelOtpGenerator
return [
    'debug' => false,
    'expiry' => env('OTP_EXPIRY', 15 * 60),
    'length' => env('OTP_LENGTH', 6),
];

```


## Usage

```php
use ClarityTech\LaravelOtpGenerator\Facades\AugmentedOTP;

$otp     =  AugmentedOTP::generateWithKey(9706353416);

$isValid =  AugmentedOTP::withKey(9706353416)->validate(123456);

$isValid =  AugmentedOTP::withKey(9706353416)->verify(123456);//does not trigger exception
```
The validate methods throws two exceptions based on which you can handle in your use case and modify the response to give for the user output as needed

```php 
use ClarityTech\LaravelOtpGenerator\Exceptions\OTPExpiredException;
use ClarityTech\LaravelOtpGenerator\Exceptions\OTPInvalidException;
```

And when you have request context available you can pass that too for more secure hash key generation internally which locks the OTP to be valid for that session / IP address only

```php
$instance = AugmentedOTP::fromRequest(Request $request, string $mobileNo);

$otp = $instance->generate();

$otp = $instance->validate(123456);
```

you can determine whether provide code is valid or not


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Manash Jyoti Sonowal](https://github.com/msonowal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
