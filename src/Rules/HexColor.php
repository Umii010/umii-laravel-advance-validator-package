
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class HexColor implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        return (bool)preg_match('/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value);
    }

    public function message(): string
    {
        return 'The :attribute must be a valid hex color (e.g., #fff or #ffffff).';
    }
}
