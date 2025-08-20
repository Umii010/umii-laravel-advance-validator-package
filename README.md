# 🌟 Umii Laravel Advance Validator Package

The **Umii Laravel Advance Validator Package** extends Laravel's validation system with extra validation rules that are not included by default.  
It helps developers write cleaner, more secure, and user-friendly validation logic.

---

## 📑 Table of Contents
- [Installation](#-installation)
- [Available Rules](#-available-rules)
- [Usage Example](#-usage-example)
- [Test JSON Payloads](#-test-json-payloads)
- [Validation Error Example](#-validation-error-example)
- [Configuration](#-configuration)
- [Why Use This Package?](#-why-use-this-package)
- [Changelog](#-changelog)
- [Author](#-author)
- [License](#-license)

---

## 🚀 Installation

**Install via Composer:**

```bash
composer require umii/laravel-advance-validator-package
```

Laravel will auto-discover the service provider.

**To publish the config file (optional):**

```bash
php artisan vendor:publish --tag=config
```

---

## 📜 Available Rules

| Rule              | Description |
|-------------------|-------------|
| `strong_password` | Must contain at least 8 characters, including uppercase, lowercase, number, and special character. |
| `username`        | Only letters, numbers, underscores (3-20 characters). |
| `phone`           | Valid phone number (supports international e.g. `+923001234567`). |
| `iban`            | Validates IBAN (International Bank Account Number). |
| `credit_card`     | Validates credit card number using Luhn algorithm. |
| `base64_image`    | Ensures input is a valid Base64-encoded image (PNG, JPG, JPEG, GIF). |
| `no_emoji`        | Rejects input containing emojis. |
| `slug`            | Valid slug format (`my-page-slug`). |
| `hex_color`       | Valid HEX color code (`#ffcc00` or `#fc0`). |
| `geo_coordinate`  | Valid latitude/longitude (`37.7749,-122.4194`). |
| `uuid_v7`         | Validates UUID version 7. |
| `alpha_spaces`    | Only letters and spaces allowed. |
| `disposable_email`| Rejects disposable email addresses. |
| `numeric_only`    | Only numbers allowed (no other characters). |
| `alpha_num_spaces`| Letters, numbers and spaces allowed. |
| `letters_only`    | Only letters allowed (no numbers or special characters). |
| `file_extension`  | Validates file extension against allowed list. |
| `youtube_url`     | Validates YouTube URL. |
| `twitter_handle`  | Validates Twitter handle (1-15 characters, optional @). |
| `ip_address`      | Validates correct IPv4/IPv6 address. |
| `json_string`     | Ensures input is valid JSON. |
| `mac_address`     | Validates MAC address format. |

---

## 🛠 Usage Example

```php
public function store(Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255|alpha_spaces|no_emoji',
        'username' => 'required|username|unique:users,username',
        'email' => 'required|email|disposable_email',
        'phone' => 'nullable|phone',
        'password' => 'required|strong_password|confirmed',
        'iban' => 'nullable|iban',
        'credit_card' => 'nullable|credit_card',
        'avatar' => 'nullable|base64_image',
        'profile_slug' => 'required|slug|unique:profiles,slug',
        'brand_color' => 'nullable|hex_color',
        'location' => 'nullable|geo_coordinate',
        'user_uuid' => 'nullable|uuid_v7',
        'age' => 'nullable|numeric_only',
        'bio' => 'nullable|alpha_num_spaces',
        'first_name' => 'nullable|letters_only',
        'document' => 'nullable|file_extension:pdf,doc,docx',
        'youtube_channel' => 'nullable|youtube_url',
        'twitter_username' => 'nullable|twitter_handle',
        'device_ip' => 'nullable|ip_address',
        'preferences' => 'nullable|json_string',
        'device_mac' => 'nullable|mac_address',
    ]);

    // Process validated data...
}
```

---

## 🧪 Test JSON Payloads

✅ **Valid Example:**

```json
{
  "name": "John Doe",
  "username": "john_doe_123",
  "email": "john.doe@example.com",
  "phone": "+923001234567",
  "password": "StrongPass@123",
  "confirm_password": "StrongPass@123",
  "iban": "DE89370400440532013000",
  "credit_card": "4539578763621486",
  "avatar": "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==",
  "profile_slug": "john-doe-profile",
  "brand_color": "#ffcc00",
  "location": "37.7749,-122.4194",
  "user_uuid": "01890c9d-3b8a-7c42-9f1b-3c4e8f1e2b7a",
  "age": "30",
  "bio": "Software Developer with 5 years experience",
  "first_name": "John",
  "document": "report.pdf",
  "youtube_channel": "https://www.youtube.com/watch?v=dQw4w9WgXcQ",
  "twitter_username": "@johndoe",
  "device_ip": "192.168.1.1",
  "preferences": "{\"theme\":\"dark\",\"notifications\":true}",
  "device_mac": "00:1B:44:11:3A:B7"
}
```

❌ **Invalid Example (will trigger errors):**

```json
{
  "name": "John 🚀",
  "username": "jd",
  "email": "temp@mailinator.com",
  "phone": "12345",
  "password": "weak",
  "confirm_password": "weak",
  "iban": "123456789",
  "credit_card": "111122223333444",
  "avatar": "data:text/plain;base64,SGVsbG8=",
  "profile_slug": "invalid slug!!",
  "brand_color": "blue",
  "location": "abc,xyz",
  "user_uuid": "not-a-uuid",
  "age": "30 years",
  "bio": "Special $characters!",
  "first_name": "John123",
  "document": "report.exe",
  "youtube_channel": "not-a-youtube-url",
  "twitter_username": "this_is_too_long_for_twitter",
  "device_ip": "999.999.999.999",
  "preferences": "not-json",
  "device_mac": "invalid-mac"
}
```

---

## ⚠️ Validation Error Example

If invalid data is sent, Laravel will return errors like:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name must not contain emojis."],
    "username": ["The username must be 3-20 characters and contain only letters, numbers, and underscores."],
    "email": ["Disposable email addresses are not allowed."],
    "password": ["The password must be at least 8 characters long, include upper/lowercase letters, a number, and a special character."],
    "iban": ["The IBAN must be a valid IBAN."],
    "credit_card": ["The credit card must be a valid credit card number."],
    "avatar": ["The avatar must be a valid Base64 encoded image."],
    "profile_slug": ["The profile slug must be a valid slug."]
  }
}
```

---

## ⚙️ Configuration

After publishing the configuration file, you can customize various validation rules:

```php
// config/umii_advance_validator.php

return [
    'disposable_email_domains' => [
        'tempmail.com',
        'mailinator.com',
        '10minutemail.com',
        // Add more disposable domains as needed
    ],

    'allowed_file_extensions' => [
        'pdf', 'doc', 'docx', 'txt',
        'jpg', 'jpeg', 'png', 'gif',
        // Add more extensions as needed
    ],
];
```

---

## 💡 Why Use This Package?

✔️ Eliminates repetitive regex rules in your projects  
✔️ Provides strong password enforcement out of the box  
✔️ Adds business-specific validation (IBAN, credit card, geo-coordinates, etc.)  
✔️ Prevents common security issues (emoji injection, disposable emails)  
✔️ Lightweight and fully integrates with Laravel validation system  
✔️ Consistent error messages across projects  
✔️ Easily extensible and configurable  

---

## 📜 Changelog

- **v1.0.0** – Initial Release 🎉

---

## 👨‍💻 Author

**Muhammad (Umii010)**  
[GitHub](https://www.github.com/Umii010)  

---

## 📄 License

This package is open-sourced software licensed under the **MIT license**.
