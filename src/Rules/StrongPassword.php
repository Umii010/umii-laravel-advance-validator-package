
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    protected int $min = 0;
    protected string $specialClass = '[^a-zA-Z\d]';

    public function setParams(array $params): self
    {
        $config = config('umii_advanced_validator.strong_password');
        $this->min = (int)($params[0] ?? ($config['min_length'] ?? 8));
        $this->specialClass = $config['special_class'] ?? $this->specialClass;
        return $this;
    }

    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        if (mb_strlen($value) < $this->min) return false;

        $hasLower = preg_match('/[a-z]/u', $value);
        $hasUpper = preg_match('/[A-Z]/u', $value);
        $hasDigit = preg_match('/\d/u', $value);
        $hasSpecial = preg_match('/' . $this->specialClass . '/u', $value);

        return $hasLower && $hasUpper && $hasDigit && $hasSpecial;
    }

    public function message(): string
    {
        return 'The :attribute must be at least ' . ($this->min ?: 8) . ' characters and include uppercase, lowercase, number, and special character.';
    }
}
