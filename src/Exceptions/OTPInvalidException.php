<?php

namespace ClarityTech\LaravelOtpGenerator\Exceptions;

use Exception;

class OTPInvalidException extends Exception
{
    const MESSAGE = 'OTP is Invalid.';

    /**
     * Create a new ExpiredException exception.
     * This should be fired when the otp store for particular secret is missing or null
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = self::MESSAGE)
    {
        parent::__construct($message);
    }

    public function getValidationErrors(): array
    {
        return [
            'otp_code' => [
                self::MESSAGE,
            ],
        ];
    }
}
