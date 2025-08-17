
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class SubdomainOf implements Rule
{
    protected string $base = '';

    public function setParams(array $params): self
    {
        $this->base = strtolower(trim($params[0] ?? ''));
        return $this;
    }

    public function passes($attribute, $value): bool
    {
        if (!is_string($value) || $this->base === '') return false;
        $value = strtolower($value);
        if ($value === $this->base) return false; // must be subdomain, not equal
        return str_ends_with($value, '.' . $this->base);
    }

    public function message(): string
    {
        return 'The :attribute must be a subdomain of ' . $this->base . '.';
    }
}
