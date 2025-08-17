
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoEmoji implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        // Basic emoji ranges; not exhaustive but practical.
        return !preg_match('/[\x{1F300}-\x{1F6FF}\x{1F900}-\x{1F9FF}\x{2600}-\x{27BF}\x{1FA70}-\x{1FAFF}]/u', $value);
    }

    public function message(): string
    {
        return 'The :attribute must not contain emoji.';
    }
}
