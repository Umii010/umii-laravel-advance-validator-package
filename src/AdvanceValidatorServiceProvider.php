<?php

namespace Umii\AdvanceValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AdvanceValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/umii_advance_validator.php' => config_path('umii_advance_validator.php'),
        ], 'config');

        // ✅ Strong password
        Validator::extend('strong_password', function ($attribute, $value) {
            return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value);
        }, 'The :attribute must be at least 8 characters long, include upper/lowercase letters, a number, and a special character.');

        // ✅ Username
        Validator::extend('username', function ($attribute, $value) {
            return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $value);
        }, 'The :attribute must be 3-20 characters and contain only letters, numbers, and underscores.');

        // ✅ Phone
        Validator::extend('phone', function ($attribute, $value) {
            return preg_match('/^\+?[0-9]{7,15}$/', $value);
        }, 'The :attribute must be a valid phone number.');

        // ✅ IBAN
        Validator::extend('iban', function ($attribute, $value) {
            return preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/', $value);
        }, 'The :attribute must be a valid IBAN.');

        // ✅ Credit card (Luhn algo)
        Validator::extend('credit_card', function ($attribute, $value) {
            $value = preg_replace('/\D/', '', $value);
            $sum = 0;
            $alt = false;
            for ($i = strlen($value) - 1; $i >= 0; $i--) {
                $n = intval($value[$i]);
                if ($alt) {
                    $n *= 2;
                    if ($n > 9) {
                        $n -= 9;
                    }
                }
                $sum += $n;
                $alt = !$alt;
            }
            return $sum % 10 == 0;
        }, 'The :attribute must be a valid credit card number.');

        // ✅ Base64 image
        Validator::extend('base64_image', function ($attribute, $value) {
            return preg_match('/^data:image\/(png|jpg|jpeg|gif);base64,/', $value);
        }, 'The :attribute must be a valid Base64 encoded image.');

        // ✅ No emoji
        Validator::extend('no_emoji', function ($attribute, $value) {
            return !preg_match('/[\x{1F600}-\x{1F64F}]/u', $value);
        }, 'The :attribute must not contain emojis.');

        // ✅ Slug
        Validator::extend('slug', function ($attribute, $value) {
            return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
        }, 'The :attribute must be a valid slug.');

        // ✅ Hex color
        Validator::extend('hex_color', function ($attribute, $value) {
            return preg_match('/^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/', $value);
        }, 'The :attribute must be a valid hex color code.');

        // ✅ Geo coordinate
        Validator::extend('geo_coordinate', function ($attribute, $value) {
            return preg_match('/^-?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*-?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', $value);
        }, 'The :attribute must be a valid geo coordinate.');

        // ✅ UUID v7
        Validator::extend('uuid_v7', function ($attribute, $value) {
            return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value);
        }, 'The :attribute must be a valid UUID v7.');

        // ✅ Alpha spaces
        Validator::extend('alpha_spaces', function ($attribute, $value) {
            return preg_match('/^[\pL\s]+$/u', $value);
        }, 'The :attribute may only contain letters and spaces.');

        // ✅ Disposable email
        Validator::extend('disposable_email', function ($attribute, $value) {
            $disposableDomains = ['tempmail.com', 'mailinator.com', '10minutemail.com'];
            $domain = strtolower(substr(strrchr($value, "@"), 1));
            return !in_array($domain, $disposableDomains);
        }, 'Disposable email addresses are not allowed.');

        /**
         * 📌 New Useful Rules
         */

        // ✅ Numeric-only
        Validator::extend('numeric_only', function ($attribute, $value) {
            return preg_match('/^[0-9]+$/', $value);
        }, 'The :attribute must contain only numbers.');

        // ✅ Alpha numeric with spaces
        Validator::extend('alpha_num_spaces', function ($attribute, $value) {
            return preg_match('/^[a-zA-Z0-9\s]+$/', $value);
        }, 'The :attribute may only contain letters, numbers and spaces.');

        // ✅ Letters only
        Validator::extend('letters_only', function ($attribute, $value) {
            return preg_match('/^[a-zA-Z]+$/', $value);
        }, 'The :attribute may only contain letters.');

        // ✅ File extension check
        Validator::extend('file_extension', function ($attribute, $value, $parameters) {
            if (!is_string($value)) return false;
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            return in_array(strtolower($ext), $parameters);
        }, 'The :attribute must have a valid extension.');

        // ✅ Youtube URL
        Validator::extend('youtube_url', function ($attribute, $value) {
            return preg_match('/^(https?\:\/\/)?(www\.youtube\.com|youtu\.?be)\/.+$/', $value);
        }, 'The :attribute must be a valid YouTube URL.');

        // ✅ Twitter handle
        Validator::extend('twitter_handle', function ($attribute, $value) {
            return preg_match('/^@?(\w){1,15}$/', $value);
        }, 'The :attribute must be a valid Twitter handle.');

        // ✅ IP address (IPv4 + IPv6)
        Validator::extend('ip_address', function ($attribute, $value) {
            return filter_var($value, FILTER_VALIDATE_IP) !== false;
        }, 'The :attribute must be a valid IP address.');

        // ✅ JSON string
        Validator::extend('json_string', function ($attribute, $value) {
            json_decode($value);
            return (json_last_error() == JSON_ERROR_NONE);
        }, 'The :attribute must be a valid JSON string.');

        // ✅ Mac Address
        Validator::extend('mac_address', function ($attribute, $value) {
            return preg_match('/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', $value);
        }, 'The :attribute must be a valid MAC address.');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/umii_advance_validator.php', 'umii_advance_validator');
    }
}
