# This is my package filament-logistics

[![Latest Version on Packagist](https://img.shields.io/packagist/v/red-jasmine/filament-logistics.svg?style=flat-square)](https://packagist.org/packages/red-jasmine/filament-logistics)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/red-jasmine/filament-logistics/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/red-jasmine/filament-logistics/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/red-jasmine/filament-logistics/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/red-jasmine/filament-logistics/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/red-jasmine/filament-logistics.svg?style=flat-square)](https://packagist.org/packages/red-jasmine/filament-logistics)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require red-jasmine/filament-logistics
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-logistics-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-logistics-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-logistics-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentLogistics = new RedJasmine\FilamentLogistics();
echo $filamentLogistics->echoPhrase('Hello, RedJasmine!');
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

- [](https://github.com/red-jasmine)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
