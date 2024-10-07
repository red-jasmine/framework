# This is my package filament-product

[![Latest Version on Packagist](https://img.shields.io/packagist/v/red-jasmine/filament-product.svg?style=flat-square)](https://packagist.org/packages/red-jasmine/filament-product)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/red-jasmine/filament-product/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/red-jasmine/filament-product/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/red-jasmine/filament-product/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/red-jasmine/filament-product/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/red-jasmine/filament-product.svg?style=flat-square)](https://packagist.org/packages/red-jasmine/filament-product)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require red-jasmine/filament-product
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-product-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-product-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-product-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentProduct = new Redjasmine\FilamentProduct();
echo $filamentProduct->echoPhrase('Hello, Redjasmine!');
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
