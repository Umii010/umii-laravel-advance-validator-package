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

        // Register custom rules
        Validator::extend('strong_password', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value);
        }, 'The :attribute must be at least 8 characters long, include upper/lowercase letters, a number, and a special character.');

        Validator::extend('username', function ($attribute, $value) {
            return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $value);
        }, 'The :attribute must be 3-20 characters and contain only letters, numbers, and underscores.');

        Validator::extend('phone', function ($attribute, $value) {
            return preg_match('/^\+?[0-9]{7,15}$/', $value);
        }, 'The :attribute must be a valid phone number.');

        Validator::extend('iban', function ($attribute, $value) {
            return preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/', $value);
        }, 'The :attribute must be a valid IBAN.');

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

        Validator::extend('base64_image', function ($attribute, $value) {
            return preg_match('/^data:image\/(png|jpg|jpeg);base64,/', $value);
        }, 'The :attribute must be a valid Base64 encoded image.');

        Validator::extend('no_emoji', function ($attribute, $value) {
            return !preg_match('/[\x{1F600}-\x{1F64F}]/u', $value);
        }, 'The :attribute must not contain emojis.');

        Validator::extend('slug', function ($attribute, $value) {
            return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
        }, 'The :attribute must be a valid slug.');

        Validator::extend('hex_color', function ($attribute, $value) {
            return preg_match('/^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/', $value);
        }, 'The :attribute must be a valid hex color code.');

        Validator::extend('geo_coordinate', function ($attribute, $value) {
            return preg_match('/^-?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*-?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', $value);
        }, 'The :attribute must be a valid geo coordinate.');

        Validator::extend('uuid_v7', function ($attribute, $value) {
            return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value);
        }, 'The :attribute must be a valid UUID v7.');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/umii_advance_validator.php', 'umii_advance_validator');
    }
}
