
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64Image implements Rule
{
    protected array $allowed = [];

    public function setParams(array $params): self
    {
        $this->allowed = array_filter(array_map('strtolower', $params));
        return $this;
    }

    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        if (!preg_match('/^data:image\/([a-zA-Z0-9.+-]+);base64,/', $value, $m)) return false;
        $ext = strtolower($m[1]);
        if ($this->allowed && !in_array($ext, $this->allowed, true)) return false;
        $base64 = substr($value, strpos($value, ',') + 1);
        $decoded = base64_decode($base64, true);
        return $decoded !== false;
    }

    public function message(): string
    {
        return 'The :attribute must be a valid base64-encoded image.';
    }
}
