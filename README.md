
# umii-laravel-advnace-vlaidator-package

Advanced validation rules for Laravel that are **not** provided by default. Supports Laravel 10 and 11 via package auto-discovery.

## Install

```bash
composer require umii/umii-laravel-advnace-vlaidator-package
```

No manual provider registration is needed (auto-discovery).

## Usage

Use custom rules in your validators like normal:

```php
$request->validate([
    'password'  => 'required|strong_password',
    'username'  => 'required|username:3,20,_,.',
    'phone'     => 'nullable|phone_e164',
    'iban'      => 'nullable|iban',
    'card'      => 'nullable|credit_card_luhn',
    'avatar'    => 'nullable|base64_image:jpeg,png,webp',
    'headline'  => 'required|word_count:3,20|no_emoji|no_html',
    'slug'      => 'required|slug',
    'color'     => 'nullable|hex_color',
    'lat'       => 'nullable|latitude',
    'lng'       => 'nullable|longitude',
    'domain'    => 'nullable|domain',
    'sub'       => 'nullable|subdomain_of:example.com',
    'uuid7'     => 'nullable|uuid_v7',
    'tags'      => 'nullable|alpha_spaces',
    'handle'    => 'nullable|no_spaces',
]);
```

## Available Rules

- `strong_password[:minLength]` – default min length is from config (8). Requires upper, lower, digit, special.
- `username:min,max,allowedExtras` – allowed extras are optional characters to allow in addition to alphanumerics (e.g., `_,.`).
- `no_spaces` – rejects any whitespace.
- `alpha_spaces` – only letters and spaces (Unicode aware).
- `slug` – lowercase words, numbers and hyphens; no leading/trailing hyphen.
- `phone_e164` – `+` and 8–15 digits (E.164).
- `domain` – validates domain like `example.com` (no scheme).
- `subdomain_of:example.com` – ensures value is subdomain of given base domain.
- `iban` – validates IBAN including checksum.
- `credit_card_luhn` – validates using the Luhn algorithm (no brand check).
- `base64_image[:ext1,ext2,...]` – validates `data:image/{ext};base64,` prefix and decodes base64.
- `no_emoji` – rejects common emoji ranges.
- `hex_color` – `#RGB` or `#RRGGBB` (case-insensitive).
- `latitude` – -90 to 90 with up to 6 decimals.
- `longitude` – -180 to 180 with up to 6 decimals.
- `no_html` – rejects strings containing `<...>` HTML tags.
- `uuid_v7` – checks for UUID version 7 format (RFC 4122-like).
- `dns_domain` – validates domain and also checks DNS A/AAAA record if supported by environment.

## Config

Publish and edit the config:

```bash
php artisan vendor:publish --tag=umii-adv-validator-config
```

`config/umii_advanced_validator.php`:

```php
return [
    'strong_password' => [
        'min_length' => 8,
        // Optional: custom special characters class (PCRE class content)
        'special_class' => r'[^a-zA-Z\d]',
    ],
];
```

## Testing (optional)

Use PHPUnit/Pest with Orchestra Testbench to test rules.

## License

MIT
