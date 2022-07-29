<?php

namespace ClarityTech\LaravelOtpGenerator;

use ClarityTech\LaravelOtpGenerator\Commands\LaravelOtpGeneratorCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelOtpGeneratorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-otp-generator')
            ->hasConfigFile();
    }
}
