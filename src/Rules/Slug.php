
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class Slug implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        return (bool)preg_match('/^(?!-)[a-z0-9-]+(?<!-)$/', $value);
    }

    public function message(): string
    {
        return 'The :attribute must be a valid slug (lowercase, numbers and hyphens; no leading or trailing hyphen).';
    }
}
