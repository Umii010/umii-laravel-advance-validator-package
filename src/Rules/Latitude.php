
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class Latitude implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_numeric($value) && !is_string($value)) return false;
        return (bool)preg_match('/^[-+]?([1-8]?\d(\.\d{1,6})?|90(\.0{1,6})?)$/', (string)$value);
    }

    public function message(): string
    {
        return 'The :attribute must be a valid latitude (-90 to 90).';
    }
}
