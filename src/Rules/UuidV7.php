
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class UuidV7 implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        // UUID v7: version nibble = 7, variant 8,9,a,b
        return (bool)preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-7[0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$/', $value);
    }

    public function message(): string
    {
        return 'The :attribute must be a valid UUID v7.';
    }
}
