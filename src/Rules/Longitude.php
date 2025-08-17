
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class Longitude implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_numeric($value) && !is_string($value)) return false;
        return (bool)preg_match('/^[-+]?((1[0-7]\d|[1-9]?\d)(\.\d{1,6})?|180(\.0{1,6})?)$/', (string)$value);
    }

    public function message(): string
    {
        return 'The :attribute must be a valid longitude (-180 to 180).';
    }
}
