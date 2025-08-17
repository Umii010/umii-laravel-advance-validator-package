
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoHtml implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        return !preg_match('/<[^>]+>/', $value);
    }

    public function message(): string
    {
        return 'The :attribute must not contain HTML.';
    }
}
