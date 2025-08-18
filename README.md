# Umii Laravel Advance Validator Package

A collection of **production-ready validation rules** that feel native to Laravel and cover real-world cases Laravel doesn't include by default.

## Installation

```bash
composer require umii/laravel-advance-validator-package
```

This package uses auto-discovery.

To publish the config:

```bash
php artisan vendor:publish --tag=config
```

### Optional: Country-specific phone validation
For `phone_country:<CC>` to work, install Google's libphonenumber:
```bash
composer require giggsey/libphonenumber-for-php
```

---

## Available Rules

| Rule | Purpose | Example |
|------|---------|---------|
| `alpha_spaces` | Letters + spaces only | `Muhammad Umer` |
| `strong_password` | 8+ chars with upper, lower, digit, special | `Str0ng!Pass` |
| `phone` | E.164 (+ and 7–15 digits). Accepts spaces/dashes/() and normalizes | `+923001234567` |
| `phone_number` | Alias of `phone` | same as `phone` |
| `phone_country:PK` | Country-specific phone using libphonenumber | `+923001234567` |
| `disposable_email` | Blocks domains from config list | `user@mailinator.com` ❌ |
| `username` | 3–20 chars, letters/digits/underscore | `umer_shahzad` |
| `iban` | Basic IBAN format | `DE44500105175407324931` |
| `credit_card` | Luhn check | `4242424242424242` |
| `base64_image` | `data:image/(png|jpg|jpeg);base64,...` | |
| `no_emoji` | Disallow emojis | |
| `emoji_only` | Allow only emojis (and whitespace) | 😀🎉 |
| `slug` | Lowercase slug with dashes | `my-awesome-post` |
| `hex_color` | Hex color (#fff or #ffffff) | `#1e90ff` |
| `geo_coordinate` | `lat, lng` pair | `31.5204, 74.3587` |
| `uuid_v7` | UUID version 7 | `01890d4c-7b2a-7d4c-9b7a-0f1a2b3c4d5e` |
| `ssn_us` | US SSN `###-##-####` or `#########` | `123-45-6789` |
| `nin_uk` | UK National Insurance Number | `QQ123456C` |
| `cpf` | Brazilian CPF | `39053344705` |
| `cnpj` | Brazilian CNPJ | `11222333000181` |
| `strong_pin[:min,:max]` | Numeric only; blocks repeated and sequential | `strong_pin:4,8` |
| `domain` | Validates domain names | `example.com` |

---

## Usage

```php
use Illuminate\Support\Facades\Validator;

$validator = Validator::make($request->all(), [
    'name'     => 'required|alpha_spaces',
    'email'    => 'required|email|unique:users,email|disposable_email',
    'phone'    => 'required|phone', // or: phone_country:PK (requires libphonenumber)
    'password' => 'required|min:8|strong_password',
    'username' => 'nullable|username',
    'ssn'      => 'nullable|ssn_us',
    'nin'      => 'nullable|nin_uk',
    'cpf'      => 'nullable|cpf',
    'cnpj'     => 'nullable|cnpj',
    'pin'      => 'nullable|strong_pin:4,8',
    'website'  => 'nullable|domain',
    'emoji'    => 'nullable|emoji_only',
]);
```

### Example failing payload (for testing)

```json
{
  "name": "John123!!!",
  "email": "fake@mailinator.com",
  "phone": "abcd1234",
  "password": "12345",
  "username": "__bad__",
  "ssn": "12-345-678",
  "nin": "AB12345Z",
  "cpf": "00000000000",
  "cnpj": "00.000.000/0000-00",
  "pin": "1234",
  "website": "-bad-.com"
}
```

### Example passing payload

```json
{
  "name": "Muhammad Umer",
  "email": "umer@example.com",
  "phone": "+923001234567",
  "password": "Str0ng!Pass",
  "username": "umer_shahzad",
  "ssn": "123-45-6789",
  "nin": "QQ123456C",
  "cpf": "39053344705",
  "cnpj": "11222333000181",
  "pin": "8064",
  "website": "example.com",
  "emoji": "🎉🎉"
}
```

---

## Configuration

Publish the config and edit `config/umii_advance_validator.php`:

```php
return [
    'enabled' => true,
    'disposable_domains' => [
        'mailinator.com',
        '10minutemail.com',
        'guerrillamail.com',
        'tempmail.com',
        'yopmail.com',
        'trashmail.com',
        'sharklasers.com',
        'throwawaymail.com',
        'getnada.com',
        'dispostable.com',
    ],
];
```

---

## Notes

- `phone_country:<ISO2>` requires `giggsey/libphonenumber-for-php`. If the library is **not** installed, the rule gracefully falls back to E.164 validation (same as `phone`).  
- Identity numbers (SSN/NIN/CPF/CNPJ) use a mix of **format checks** and **check-digit logic** where applicable. Always confirm legal/compliance needs for your region.

---

## License

MIT
