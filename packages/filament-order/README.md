# This is my package filament-order

[![Latest Version on Packagist](https://img.shields.io/packagist/v/red-jasmine/filament-order.svg?style=flat-square)](https://packagist.org/packages/red-jasmine/filament-order)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/red-jasmine/filament-order/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/red-jasmine/filament-order/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/red-jasmine/filament-order/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/red-jasmine/filament-order/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/red-jasmine/filament-order.svg?style=flat-square)](https://packagist.org/packages/red-jasmine/filament-order)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require red-jasmine/filament-order
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-order-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-order-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-order-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentOrder = new RedJasmine\FilamentOrder();
echo $filamentOrder->echoPhrase('Hello, RedJasmine!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [liushoukun](https://github.com/red-jasmine)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
