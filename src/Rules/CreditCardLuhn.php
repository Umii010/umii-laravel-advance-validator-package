
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class CreditCardLuhn implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        $digits = preg_replace('/\D/', '', $value);
        if ($digits === '' || strlen($digits) < 12 || strlen($digits) > 19) return false;

        $sum = 0;
        $parity = strlen($digits) % 2;
        for ($i = 0; $i < strlen($digits); $i++) {
            $d = (int)$digits[$i];
            if ($i % 2 == $parity) {
                $d *= 2;
                if ($d > 9) $d -= 9;
            }
            $sum += $d;
        }
        return ($sum % 10) === 0;
    }

    public function message(): string
    {
        return 'The :attribute is not a valid card number.';
    }
}
