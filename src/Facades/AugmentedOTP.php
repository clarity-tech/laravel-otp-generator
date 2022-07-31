<?php

namespace ClarityTech\LaravelOtpGenerator\Facades;

use ClarityTech\LaravelOtpGenerator\LaravelOtpGenerator;
use Illuminate\Support\Facades\Facade;

class AugmentedOTP extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LaravelOtpGenerator::class;
    }
}
