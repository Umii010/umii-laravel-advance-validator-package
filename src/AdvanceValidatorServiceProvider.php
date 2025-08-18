<?php

namespace Umii\AdvanceValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AdvanceValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/umii_advance_validator.php' => config_path('umii_advance_validator.php'),
        ], 'config');

        /**
         * ======================
         * Custom Validation Rules
         * ======================
         */

        // Alpha spaces (letters + spaces only)
        Validator::extend('alpha_spaces', function ($attribute, $value) {
            return is_string($value) && preg_match('/^[\pL\s]+$/u', $value);
        }, 'The :attribute may only contain letters and spaces.');

        // Strong password
        Validator::extend('strong_password', function ($attribute, $value) {
            return is_string($value) && preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $value);
        }, 'The :attribute must be at least 8 characters long and include upper/lowercase letters, a number, and a special character.');

        // Phone (E.164). Accepts + and 7-15 digits. Sanitizes unicode plus and spaces/dashes.
        Validator::extend('phone', function ($attribute, $value) {
            if (!is_string($value) && !is_numeric($value)) return false;
            $v = trim((string)$value);
            $v = str_replace('＋', '+', $v); // normalize fullwidth plus
            $v = preg_replace('/[\s\-()]/', '', $v); // strip spaces, dashes, parentheses
            return preg_match('/^\+\d{7,15}$/', $v) === 1;
        }, 'The :attribute must be a valid phone number in E.164 format (e.g. +923001234567).');

        // Alias to support 'phone_number' as well
        Validator::extend('phone_number', function ($attribute, $value) {
            return Validator::make([$attribute => $value], [$attribute => 'phone'])->passes();
        }, 'The :attribute must be a valid phone number.');

        // Country-specific phone using libphonenumber (giggsey/libphonenumber-for-php)
        Validator::extend('phone_country', function ($attribute, $value, $parameters) {
            if (!class_exists('libphonenumber\PhoneNumberUtil')) {
                // Fallback: require E.164 if library not installed
                return Validator::make([$attribute => $value], [$attribute => 'phone'])->passes();
            }
            if (!is_string($value) && !is_numeric($value)) return false;
            $region = $parameters[0] ?? null; // e.g., 'PK', 'US', 'GB'
            if (!$region) return false;

            $v = trim((string)$value);
            $v = str_replace('＋', '+', $v);

            try {
                $util = \libphonenumber\PhoneNumberUtil::getInstance();
                $defaultRegion = (strpos($v, '+') === 0) ? null : $region;
                $num = $util->parse($v, $defaultRegion);
                return $util->isValidNumberForRegion($num, $region);
            } catch (\libphonenumber\NumberParseException $e) {
                return false;
            }
        }, 'The :attribute is not a valid phone number for the specified country.');

        // Disposable email (from config list)
        Validator::extend('disposable_email', function ($attribute, $value) {
            if (!is_string($value)) return false;
            $parts = explode('@', strtolower($value));
            if (count($parts) !== 2) return false;
            $domain = $parts[1];
            $list = (array) config('umii_advance_validator.disposable_domains', []);
            foreach ($list as $bad) {
                $bad = strtolower($bad);
                if ($domain === $bad || str_ends_with($domain, '.'.$bad)) {
                    return false;
                }
            }
            return true;
        }, 'Disposable email addresses are not allowed.');

        // Username
        Validator::extend('username', function ($attribute, $value) {
            return is_string($value) && preg_match('/^[a-zA-Z0-9_]{3,20}$/', $value);
        }, 'The :attribute must be 3-20 characters and contain only letters, numbers, and underscores.');

        // IBAN (basic format check)
        Validator::extend('iban', function ($attribute, $value) {
            return is_string($value) && preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/', str_replace(' ', '', strtoupper($value)));
        }, 'The :attribute must be a valid IBAN.');

        // Credit card (Luhn check)
        Validator::extend('credit_card', function ($attribute, $value) {
            $digits = preg_replace('/\D/', '', (string)$value);
            if ($digits === '') return false;
            $sum = 0; $alt = false;
            for ($i = strlen($digits) - 1; $i >= 0; $i--) {
                $n = intval($digits[$i]);
                if ($alt) {
                    $n *= 2;
                    if ($n > 9) $n -= 9;
                }
                $sum += $n;
                $alt = !$alt;
            }
            return $sum % 10 === 0;
        }, 'The :attribute must be a valid credit card number.');

        // Base64 image
        Validator::extend('base64_image', function ($attribute, $value) {
            return is_string($value) && preg_match('/^data:image\/(png|jpg|jpeg);base64,/', $value);
        }, 'The :attribute must be a valid Base64 encoded image.');

        // No emoji
        Validator::extend('no_emoji', function ($attribute, $value) {
            return is_string($value) && !preg_match('/[\x{1F300}-\x{1FAFF}]/u', $value);
        }, 'The :attribute must not contain emojis.');

        // Emoji only (must include at least one, and only emoji/whitespace)
        Validator::extend('emoji_only', function ($attribute, $value) {
            if (!is_string($value)) return false;
            $trimmed = preg_replace('/\s+/u', '', $value);
            if ($trimmed === '') return false;
            $without_emoji = preg_replace('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]+/u', '', $trimmed);
            return $without_emoji === '';
        }, 'The :attribute must contain only emoji characters.');

        // Slug
        Validator::extend('slug', function ($attribute, $value) {
            return is_string($value) && preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
        }, 'The :attribute must be a valid slug.');

        // Hex color
        Validator::extend('hex_color', function ($attribute, $value) {
            return is_string($value) && preg_match('/^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/', $value);
        }, 'The :attribute must be a valid hex color code.');

        // Geo coordinate
        Validator::extend('geo_coordinate', function ($attribute, $value) {
            return is_string($value) && preg_match('/^-?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*-?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', $value);
        }, 'The :attribute must be a valid geo coordinate.');

        // UUID v7
        Validator::extend('uuid_v7', function ($attribute, $value) {
            return is_string($value) && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value);
        }, 'The :attribute must be a valid UUID v7.');

        // US SSN (###-##-#### or #########) basic format
        Validator::extend('ssn_us', function ($attribute, $value) {
            if (!is_string($value) && !is_numeric($value)) return false;
            $v = (string)$value;
            return preg_match('/^(\d{3}-\d{2}-\d{4}|\d{9})$/', $v) === 1;
        }, 'The :attribute must be a valid US SSN.');

        // UK NIN
        Validator::extend('nin_uk', function ($attribute, $value) {
            if (!is_string($value)) return false;
            $v = strtoupper(str_replace(' ', '', $value));
            return preg_match('/^[A-CEGHJ-PR-TW-Z]{2}\d{6}[A-D]?$/', $v) === 1;
        }, 'The :attribute must be a valid UK National Insurance Number.');

        // Brazilian CPF
        Validator::extend('cpf', function ($attribute, $value) {
            if (!is_string($value) && !is_numeric($value)) return false;
            $cpf = preg_replace('/\D/', '', (string)$value);
            if (strlen($cpf) != 11 || preg_match('/^(\d)\1{10}$/', $cpf)) return false;
            for ($t = 9; $t < 11; $t++) {
                $d = 0;
                for ($c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$t] != $d) return false;
            }
            return true;
        }, 'The :attribute must be a valid CPF.');

        // Brazilian CNPJ
        Validator::extend('cnpj', function ($attribute, $value) {
            if (!is_string($value) && !is_numeric($value)) return false;
            $cnpj = preg_replace('/\D/', '', (string)$value);
            if (strlen($cnpj) != 14 || preg_match('/^(\d)\1{13}$/', $cnpj)) return false;
            $calc = function($cnpj, $length) {
                $weights = $length === 12 ? [5,4,3,2,9,8,7,6,5,4,3,2] : [6,5,4,3,2,9,8,7,6,5,4,3,2];
                $sum = 0;
                for ($i = 0; $i < count($weights); $i++) {
                    $sum += intval($cnpj[$i]) * $weights[$i];
                }
                $rest = $sum % 11;
                return ($rest < 2) ? 0 : 11 - $rest;
            };
            $d1 = $calc($cnpj, 12);
            if (intval($cnpj[12]) !== $d1) return false;
            $d2 = $calc($cnpj, 13);
            if (intval($cnpj[13]) !== $d2) return false;
            return true;
        }, 'The :attribute must be a valid CNPJ.');

        // Strong PIN: numeric, length between 4-12, not repeated or sequential
        Validator::extend('strong_pin', function ($attribute, $value, $parameters) {
            $min = isset($parameters[0]) ? intval($parameters[0]) : 4;
            $max = isset($parameters[1]) ? intval($parameters[1]) : 12;
            if (!is_string($value) && !is_numeric($value)) return false;
            $pin = (string)$value;
            if (!preg_match('/^\d{' . $min . ',' . $max . '}$/', $pin)) return false;
            if (preg_match('/^(\d)\1+$/', $pin)) return false; // all same
            $asc = '01234567890123456789';
            $desc = '98765432109876543210';
            if (strpos($asc, $pin) !== false || strpos($desc, $pin) !== false) return false;
            return true;
        }, 'The :attribute must be a strong PIN.');

        // Domain validation
        Validator::extend('domain', function ($attribute, $value) {
            if (!is_string($value)) return false;
            $v = strtolower(trim($value));
            if (filter_var($v, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false) return false;
            return preg_match('/^[a-z0-9-]+(\.[a-z0-9-]+)+$/', $v) === 1;
        }, 'The :attribute must be a valid domain name.');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/umii_advance_validator.php', 'umii_advance_validator');
    }
}
