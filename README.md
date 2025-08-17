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

- `strong_password`
- `username`
- `phone`
- `iban`
- `credit_card`
- `base64_image`
- `no_emoji`
- `slug`
- `hex_color`
- `geo_coordinate`
- `uuid_v7`

## Example Usage

```php
$request->validate([
    'password' => 'required|strong_password',
    'username' => 'required|username',
    'phone' => 'nullable|phone',
]);
```

## Author

**Muhammad Umer Shahzad**  
📧 umii020@hotmail.com

## License

MIT
