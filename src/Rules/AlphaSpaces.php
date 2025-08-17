
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class AlphaSpaces implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        // Unicode letters and spaces
        return (bool)preg_match('/^[\p{L}\s]+$/u', $value);
    }

    public function message(): string
    {
        return 'The :attribute may only contain letters and spaces.';
    }
}
