
<?php

namespace Umii\AdvancedValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class Iban implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) return false;
        $iban = strtoupper(preg_replace('/\s+/', '', $value));
        if (!preg_match('/^[A-Z]{2}\d{2}[A-Z0-9]{1,30}$/', $iban)) return false;
        // Move first 4 chars to end
        $rearranged = substr($iban, 4) . substr($iban, 0, 4);
        // Replace letters with numbers (A=10..Z=35)
        $numeric = '';
        foreach (str_split($rearranged) as $ch) {
            if (ctype_alpha($ch)) {
                $numeric .= (ord($ch) - 55);
            } else {
                $numeric .= $ch;
            }
        }
        // Compute mod 97
        $mod = 0;
        foreach (str_split($numeric, 7) as $chunk) {
            $mod = (int)(($mod . $chunk) % 97);
        }
        return $mod == 1;
    }

    public function message(): string
    {
        return 'The :attribute must be a valid IBAN.';
    }
}
