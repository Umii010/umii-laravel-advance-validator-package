
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class DnsDomain implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        if (!preg_match('/^(?=.{1,253}$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])\.)+[a-z]{2,}$/i', $value)) {
            return false;
        }
        if (function_exists('checkdnsrr')) {
            return checkdnsrr($value, 'A') || checkdnsrr($value, 'AAAA');
        }
        // If DNS check is not available, we still pass based on syntax
        return true;
    }

    public function message(): string
    {
        return 'The :attribute must be a resolvable domain.';
    }
}
