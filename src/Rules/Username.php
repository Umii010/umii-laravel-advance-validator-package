
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class Username implements Rule
{
    protected int $min = 3;
    protected int $max = 20;
    protected string $extras = '';

    public function setParams(array $params): self
    {
        $this->min = isset($params[0]) ? (int)$params[0] : 3;
        $this->max = isset($params[1]) ? (int)$params[1] : 20;
        $this->extras = isset($params[2]) ? preg_quote($params[2], '/') : '';
        return $this;
    }

    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        $allowed = 'A-Za-z0-9' . $this->extras;
        $pattern = '/^[' . $allowed . ']{' . $this->min . ',' . $this->max . '}$/u';
        return (bool)preg_match($pattern, $value);
    }

    public function message(): string
    {
        return 'The :attribute must be ' . $this->min . '-' . $this->max . ' characters and may contain letters, numbers' . ($this->extras ? ', and ' . $this->extras : '') . '.';
    }
}
