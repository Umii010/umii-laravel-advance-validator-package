
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneE164 implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        return (bool)preg_match('/^\+?[1-9]\d{7,14}$/', $value);
    }

    public function message(): string
    {
        return 'The :attribute must be a valid international phone number (E.164).';
    }
}
