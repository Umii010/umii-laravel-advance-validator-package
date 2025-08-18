# Umii Laravel Advance Validator Package

This package provides **extra validation rules** for Laravel projects that are not available by default.

## Installation

```bash
composer require umii/laravel-advance-validator-package
```

Laravel will auto-discover the service provider.

To publish the config file:

```bash
php artisan vendor:publish --tag=config
```

## Available Rules

- `alpha_spaces` — Letters (any language) and single spaces only; no leading/trailing/double spaces.
- `username` — 3–20 chars, starts with a letter, contains letters/numbers/underscores, no double underscores.
- `phone_number` — Basic international validation (10–15 digits). Accepts `+`, space, `()`, `-` as separators.
- `strong_password` — Min 8 chars, includes upper, lower, digit, and special; no spaces.
- `disposable_email` — Blocks emails from domains listed in `config/umii_advance_validator.php` (`disposable_domains`).
- `geo_coordinate` — Validates a `latitude, longitude` pair.
- `uuid_v7` — Validates UUID v7 format.

## Usage

```php
$validator = Validator::make($request->all(), [
    'name'     => 'required|alpha_spaces',
    'email'    => 'required|email|unique:users,email|disposable_email',
    'phone'    => 'required|phone_number',
    'password' => 'required|min:8|strong_password',
    'username' => 'nullable|username',
]);
```

## Configuration

`config/umii_advance_validator.php`:

```php
return [
    'enabled' => true,
    'disposable_domains' => [
        'mailinator.com',
        '10minutemail.com',
        // ...
    ],
];
```

## Author

**Muhammad Umer Shahzad**  
📧 umii020@hotmail.com

## License

MIT
