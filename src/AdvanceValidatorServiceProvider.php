<?php

namespace Umii\AdvanceValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AdvanceValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/umii_advance_validator.php' => config_path('umii_advance_validator.php'),
        ], 'config');

        if (!config('umii_advance_validator.enabled', true)) {
            return;
        }

        /**
         * alpha_spaces: Only letters (any locale) and spaces, no leading/trailing/double spaces.
         */
        Validator::extend('alpha_spaces', function ($attribute, $value) {
            if (!is_string($value)) return false;
            // Normalize spaces
            $value = trim($value);
            if ($value === '') return false;
            // Reject multiple consecutive spaces
            if (preg_match('/\s{2,}/u', $value)) return false;
            // Letters and spaces only (unicode aware)
            return preg_match('/^\p{L}+(?:\s\p{L}+)*$/u', $value) === 1;
        }, 'The :attribute may only contain letters and single spaces.');

        /**
         * username: 3–20 chars, letters, numbers, underscores; must start with letter; no consecutive underscores.
         */
        Validator::extend('username', function ($attribute, $value) {
            if (!is_string($value)) return false;
            if (!preg_match('/^[A-Za-z][A-Za-z0-9_]{2,19}$/', $value)) return false;
            if (strpos($value, '__') !== false) return false;
            return true;
        }, 'The :attribute must start with a letter and may contain letters, numbers, and underscores (3–20 chars).');

        /**
         * phone_number: E.164-like or local digits. Accepts optional + and separators. Validates 10–15 digits total.
         */
        Validator::extend('phone_number', function ($attribute, $value) {
            if (!is_string($value) && !is_numeric($value)) return false;
            $s = (string) $value;
            // Remove common separators
            $digits = preg_replace('/[^0-9]/', '', $s);
            $len = strlen($digits);
            if ($len < 10 || $len > 15) return false;
            // Optional leading + with rest digits and separators
            return preg_match('/^\+?[0-9\s().-]{10,20}$/', $s) === 1;
        }, 'The :attribute must be a valid phone number.');

        /**
         * strong_password: min 8, at least 1 upper, 1 lower, 1 digit, 1 special, no spaces.
         */
        Validator::extend('strong_password', function ($attribute, $value) {
            if (!is_string($value)) return false;
            if (strlen($value) < 8) return false;
            if (preg_match('/\s/', $value)) return false;
            $upper = preg_match('/[A-Z]/', $value);
            $lower = preg_match('/[a-z]/', $value);
            $digit = preg_match('/[0-9]/', $value);
            $special = preg_match('/[^A-Za-z0-9]/', $value);
            return $upper && $lower && $digit && $special;
        }, 'The :attribute must be at least 8 characters and include upper, lower, number, and special character.');

        /**
         * disposable_email: reject emails from configured disposable domains.
         */
        Validator::extend('disposable_email', function ($attribute, $value) {
            if (!is_string($value)) return false;
            $parts = explode('@', strtolower($value));
            if (count($parts) !== 2) return false;
            $domain = $parts[1];
            $list = (array) config('umii_advance_validator.disposable_domains', []);
            // Exact domain or subdomain match
            foreach ($list as $bad) {
                $bad = strtolower($bad);
                if ($domain === $bad) return false;
                if (str_ends_with($domain, '.'.$bad)) return false;
            }
            return true;
        }, 'The :attribute domain is not allowed.');

        /**
         * geo_coordinate (latitude, longitude) pair.
         */
        Validator::extend('geo_coordinate', function ($attribute, $value) {
            if (!is_string($value)) return false;
            return preg_match('/^-?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*-?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', $value) === 1;
        }, 'The :attribute must be a valid geo coordinate.');

        /**
         * uuid_v7
         */
        Validator::extend('uuid_v7', function ($attribute, $value) {
            if (!is_string($value)) return false;
            return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value) === 1;
        }, 'The :attribute must be a valid UUID v7.');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/umii_advance_validator.php', 'umii_advance_validator');
    }
}
