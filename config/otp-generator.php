<?php

// config for ClarityTech/LaravelOtpGenerator
return [
    'debug' => false,
    'expiry' => env('OTP_EXPIRY', 15 * 60),
    'length' => env('OTP_LENGTH', 6),
];
