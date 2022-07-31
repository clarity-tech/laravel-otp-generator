<?php

namespace ClarityTech\LaravelOtpGenerator\Traits;

use Illuminate\Support\Str;

trait HasOtp
{
    /**
     * A helper to be used in the model for which the otp is generated
     * say example you are generating for User Model use this trait in the model
     */
    public function getOtpKey(): string
    {
        return Str::slug(class_basename(self::class).$this->getKeyName().$this->getKey());
    }
}
