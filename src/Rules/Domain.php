
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class Domain implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        // Requires at least one dot and TLD 2+ letters; no scheme.
        return (bool)preg_match('/^(?=.{1,253}$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])\.)+[a-z]{2,}$/i', $value);
    }

    public function message(): string
    {
        return 'The :attribute must be a valid domain (e.g., example.com).';
    }
}
