# Spatie Permission Generate

[![Latest Version on Packagist](https://img.shields.io/packagist/v/karson/mpesa-php-sdk.svg?style=flat-square)](https://packagist.org/packages/karson/mpesa-php-sdk)
[![Build Status](https://img.shields.io/travis/karson/mpesa-php-sdk/master.svg?style=flat-square)](https://travis-ci.org/karson/mpesa-php-sdk)
[![Quality Score](https://img.shields.io/scrutinizer/g/karson/mpesa-php-sdk.svg?style=flat-square)](https://scrutinizer-ci.com/g/karson/mpesa-php-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/karson/mpesa-php-sdk.svg?style=flat-square)](https://packagist.org/packages/karson/mpesa-php-sdk)

Package to generate permissions from spatie/laravel-permission taking directory, controller class name and methods 
    ex:. Admin\UserController with index method => will have the permission `admin-user-index`)

## Installation

You can install the package via composer:

```bash
composer require jefremassingue/spatie-permission-generate
```

## Usage

``` php
// Set the api and public key as follows . Copy it from Mpesa Developer Console (https://developer.mpesa.vm.co.mz/) .
    $hasGenarate = Jefre\SpatiePermissionGenerate\SpatiePermissionGenerate::synchronizelPermission();

```
### Instalation in Laravel

``` php
// Set the keys in your .env file

SPG_CONTROLLERS_ROOT_PATH='app/Http/Controllers',
SPG_IGNORE_CLASSES_FILES='Controller, Other classes you want ignore'
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email jefremassingue.jm@gmail.com instead of using the issue tracker.

## Credits

- [Jefre Massingue](https://github.com/jefremassingue)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
