# Umii Laravel Advance Validator Package

The **Umii Laravel Advance Validator Package** extends Laravel's
validation system with **extra validation rules** that are not included
by default.\
It helps developers write **cleaner, more secure, and user-friendly**
validation logic.

------------------------------------------------------------------------

## 📑 Table of Contents

-   [Installation](#-installation)\
-   [Available Rules](#-available-rules)\
-   [Usage Example](#-usage-example)\
-   [Test JSON Payloads](#-test-json-payloads)\
-   [Validation Error Example](#-validation-error-example)\
-   [Why Use This Package?](#-why-use-this-package)\
-   [Author](#-author)\
-   [License](#-license)

------------------------------------------------------------------------

## 🚀 Installation

Install via Composer:


``` bash
composer require umii/laravel-advance-validator-package
```

Laravel will auto-discover the service provider.

To publish the config file (optional):

``` bash
php artisan vendor:publish --tag=config
```

------------------------------------------------------------------------

## 📜 Available Rules

  -----------------------------------------------------------------------
  Rule                                       Description
  ------------------------------------------ ----------------------------
  **`strong_password`**                      Must contain at least 8
                                             characters, including
                                             uppercase, lowercase,
                                             number, and special
                                             character.

  **`username`**                             Only letters, numbers,
                                             underscores, and dashes
                                             allowed.

  **`phone`**                                Valid phone number (supports
                                             international
                                             e.g. `+923001234567`).

  **`iban`**                                 Validates IBAN
                                             (International Bank Account
                                             Number).

  **`credit_card`**                          Validates credit card number
                                             using Luhn algorithm.

  **`base64_image`**                         Ensures input is a valid
                                             Base64-encoded image.

  **`no_emoji`**                             Rejects input containing
                                             emojis.

  **`slug`**                                 Valid slug format
                                             (`my-page-slug`).

  **`hex_color`**                            Valid HEX color code
                                             (`#ffcc00`).

  **`geo_coordinate`**                       Valid latitude/longitude
                                             (`37.7749,-122.4194`).

  **`uuid_v7`**                              Validates UUID version 7.

  **`youtube`**                              Validates YouTube video URL.

  **`twitter`**                              Validates Twitter handle
                                             (must start with `@`).

  **`ip`**                                   Validates correct IPv4/IPv6
                                             address.

  **`json_data`**                            Ensures input is valid JSON.

  **`mac`**                                  Validates MAC address
                                             format.
  -----------------------------------------------------------------------

------------------------------------------------------------------------

## 🛠 Usage Example

``` php
public function store(Request $request)
{
    $validated = $request->validate([
        'name'          => 'required|string|max:255|no_emoji',
        'username'      => 'required|username|unique:users,username',
        'email'         => 'required|email',
        'phone'         => 'nullable|phone',
        'password'      => 'required|strong_password',
        'iban'          => 'nullable|iban',
        'credit_card'   => 'nullable|credit_card',
        'slug'          => 'nullable|slug',
        'hex_color'     => 'nullable|hex_color',
        'geo_coordinate'=> 'nullable|geo_coordinate',
        'uuid_v7'       => 'nullable|uuid_v7',
        'youtube'       => 'nullable|youtube',
        'twitter'       => 'nullable|twitter',
        'ip'            => 'nullable|ip',
        'json_data'     => 'nullable|json_data',
        'mac'           => 'nullable|mac',
    ]);
}
```

------------------------------------------------------------------------

## 🧪 Test JSON Payloads

✅ **Valid Example:**

``` json
{
  "name": "John Doe",
  "username": "john_doe",
  "email": "john.doe@example.com",
  "phone": "+923001234567",
  "password": "StrongPass@123",
  "iban": "DE89370400440532013000",
  "credit_card": "4539578763621486",
  "slug": "john-doe-profile",
  "hex_color": "#ffcc00",
  "geo_coordinate": "37.7749,-122.4194",
  "uuid_v7": "01890c9d-3b8a-7c42-9f1b-3c4e8f1e2b7a",
  "youtube": "https://www.youtube.com/watch?v=dQw4w9WgXcQ",
  "twitter": "@johndoe",
  "ip": "192.168.1.1",
  "json_data": "{\"key\":\"value\"}",
  "mac": "00:1B:44:11:3A:B7"
}
```

❌ **Invalid Example (will trigger errors):**

``` json
{
  "name": "John 🚀",
  "username": "jd",
  "email": "invalid-email@",
  "phone": "12345",
  "password": "weak",
  "iban": "123456789",
  "credit_card": "111122223333444",
  "slug": "invalid slug!!",
  "hex_color": "blue",
  "geo_coordinate": "abc,xyz",
  "uuid_v7": "not-a-uuid",
  "youtube": "not-a-youtube-url",
  "twitter": "not-an-@handle",
  "ip": "999.999.999.999",
  "json_data": "not-json",
  "mac": "invalid-mac"
}
```

------------------------------------------------------------------------

## ⚠️ Validation Error Example

If invalid data is sent, Laravel will return errors like:

``` json
{
  "errors": {
    "password": ["The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character."],
    "username": ["The username format is invalid."],
    "phone": ["The phone format is invalid."],
    "iban": ["The IBAN is invalid."],
    "credit_card": ["The credit card number is invalid."],
    "youtube": ["The YouTube URL is invalid."]
  }
}
```

------------------------------------------------------------------------

## 💡 Why Use This Package?

✔️ Eliminates repetitive regex rules.\
✔️ Provides **strong password enforcement**.\
✔️ Adds **business-specific validation** (IBAN, credit card,
geo-coordinates, YouTube, Twitter, etc.).\
✔️ Lightweight and **fully integrates** with Laravel validation.

------------------------------------------------------------------------

## 👨‍💻 Author

**Muhammad Umer Shahzad**\
📧 umii020@hotmail.com

------------------------------------------------------------------------

## 📜 License

This package is open-sourced software licensed under the **MIT
License**.
