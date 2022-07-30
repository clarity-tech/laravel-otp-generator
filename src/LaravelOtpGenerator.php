<?php

namespace ClarityTech\LaravelOtpGenerator;

use ClarityTech\LaravelOtpGenerator\Exceptions\OTPExpiredException;
use ClarityTech\LaravelOtpGenerator\Exceptions\OTPInvalidException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LaravelOtpGenerator
{
    protected int $_expiry;

    protected bool $_debug;

    /**
     * Create AugmentedOTP object by the identifer/ key
     */
    public function __construct(protected ?string $_key = null)
    {
        $this->_debug = (bool) config('otp-generator.debug', false);
        $this->_expiry = (int) config('otp-generator.expiry', 15 * 60);
    }

    public static function create(string $key): static
    {
        return new static($key);
    }

    /**
     * Create a instance with key from the ip and a mobile no to simple usage
     * TODO: we can create a interface for implementation to the end user
     */
    public static function fromRequest(Request $request, string $mobileNo): static
    {
        $key = 'augmentedOTP-'.sha1(implode('|', [$request->ip(), $mobileNo]));

        $instance = static::create($key);

        return $instance;
    }

    public function generateWithKey(string $key)
    {
        $otp = $this->withKey($key)
            ->generate();

        return $otp;
    }

    public function getKey()
    {
        if (is_null($this->_key)) {
            throw new \InvalidArgumentException('Please set a key to identify the OTP for verifying later.');
        }

        return $this->_key;
    }

    public function withKey(string $suffix)
    {
        $this->_key = 'augmentedOTP-'.$suffix;

        return $this;
    }

    public function getExpiry()
    {
        return $this->_expiry;
    }

    public function setExpiry(int $expireInSeconds)
    {
        $this->_expiry = $expireInSeconds;

        return $this;
    }

    public function generateRandom()
    {
        return rand(100000, 999999);
    }

    /**
     * Set a new otp to the storage
     *
     * @return void
     */
    public function setOtp($current)
    {
        $otps = $this->retrieveOtps();

        if (is_null($otps) || ! is_array($otps)) {
            $otps = [];
        }

        $otps[] = $current;

        Cache::put($this->getKey(), $otps, $this->getExpiry());

        $this->logOTP($current);
    }

    public function generate()
    {
        $current = $this->generateRandom();

        $this->setOtp($current);

        return $current;
    }

    public function retrieveOtps()
    {
        $otps = Cache::get($this->getKey());

        return $otps;
    }

    /**
     * Returns the present/current otps from the storage
     *
     * @return array returns array of otps
     */
    public function getOtps(): array
    {
        $otps = $this->retrieveOtps();

        if (is_null($otps)) {
            throw new OTPExpiredException();
        }

        return $otps;
    }

    /**
     * Verify the OTP against the generated otp
     *
     * @param  string  $code
     * @param  bool  $clear Whether to clear/clear the otps from the storage on success helpful when only need to check against the given code
     * @return bool True if success, false if failure
     */
    public function verify($code, bool $clear = true): bool
    {
        $otps = $this->getOtps();

        $existsInOtps = in_array($code, $otps);

        if ($existsInOtps && $clear) {
            $this->clear();
        }

        return $existsInOtps;
    }

    /**
     * Clears the set of OTPs from the storage
     *
     * @return void
     */
    public function clear()
    {
        Cache::forget($this->getKey());
    }

    /**
     * Validate/Verify the OTP against the generated otp and throw exception if either it is invalid or expired
     *
     * @param  string  $code
     * @param  bool  $clear Whether to clear/clear the otps from the storage on success helpful when only need to check against the given code
     */
    public function validate($code, bool $clear = true): void
    {
        $isValid = $this->verify($code, $clear);

        if (! $isValid) {
            throw new OTPInvalidException();
        }
    }

    /**
     * Log OTP
     *
     * @param  string|null  $otp
     * @return void
     */
    public function logOTP(string $otp = null): void
    {
        if ($this->_debug) {
            logger("Your OTP is: $otp");
        }
    }
}
