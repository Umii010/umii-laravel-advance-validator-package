
<?php

namespace Umii\AdvancedValidator;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Umii\AdvancedValidator\Rules\{
    StrongPassword,
    Username,
    NoSpaces,
    AlphaSpaces,
    Slug,
    PhoneE164,
    Domain,
    SubdomainOf,
    Iban,
    CreditCardLuhn,
    Base64Image,
    NoEmoji,
    HexColor,
    Latitude,
    Longitude,
    NoHtml,
    UuidV7,
    DnsDomain
};

class AdvancedValidationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/umii_advanced_validator.php', 'umii_advanced_validator');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/umii_advanced_validator.php' => config_path('umii_advanced_validator.php'),
        ], 'umii-adv-validator-config');

        // Register rules
        Validator::extend('strong_password', fn($attr, $val, $params, $v) => (new StrongPassword)->setParams($params)->passes($attr, $val), (new StrongPassword)->message());
        Validator::extend('username',        fn($attr, $val, $params, $v) => (new Username)->setParams($params)->passes($attr, $val), (new Username)->message());
        Validator::extend('no_spaces',       fn($attr, $val) => (new NoSpaces)->passes($attr, $val), (new NoSpaces)->message());
        Validator::extend('alpha_spaces',    fn($attr, $val) => (new AlphaSpaces)->passes($attr, $val), (new AlphaSpaces)->message());
        Validator::extend('slug',            fn($attr, $val) => (new Slug)->passes($attr, $val), (new Slug)->message());
        Validator::extend('phone_e164',      fn($attr, $val) => (new PhoneE164)->passes($attr, $val), (new PhoneE164)->message());
        Validator::extend('domain',          fn($attr, $val) => (new Domain)->passes($attr, $val), (new Domain)->message());
        Validator::extend('subdomain_of',    fn($attr, $val, $params) => (new SubdomainOf)->setParams($params)->passes($attr, $val), (new SubdomainOf)->message());
        Validator::extend('iban',            fn($attr, $val) => (new Iban)->passes($attr, $val), (new Iban)->message());
        Validator::extend('credit_card_luhn',fn($attr, $val) => (new CreditCardLuhn)->passes($attr, $val), (new CreditCardLuhn)->message());
        Validator::extend('base64_image',    fn($attr, $val, $params) => (new Base64Image)->setParams($params)->passes($attr, $val), (new Base64Image)->message());
        Validator::extend('no_emoji',        fn($attr, $val) => (new NoEmoji)->passes($attr, $val), (new NoEmoji)->message());
        Validator::extend('hex_color',       fn($attr, $val) => (new HexColor)->passes($attr, $val), (new HexColor)->message());
        Validator::extend('latitude',        fn($attr, $val) => (new Latitude)->passes($attr, $val), (new Latitude)->message());
        Validator::extend('longitude',       fn($attr, $val) => (new Longitude)->passes($attr, $val), (new Longitude)->message());
        Validator::extend('no_html',         fn($attr, $val) => (new NoHtml)->passes($attr, $val), (new NoHtml)->message());
        Validator::extend('uuid_v7',         fn($attr, $val) => (new UuidV7)->passes($attr, $val), (new UuidV7)->message());
        Validator::extend('dns_domain',      fn($attr, $val) => (new DnsDomain)->passes($attr, $val), (new DnsDomain)->message());
    }
}
