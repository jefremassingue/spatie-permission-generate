# Spatie Permission Generate

Spatie Permission Generate is a package that generates permissions for spatie/laravel-permission based on the directory, controller class name, and methods. 

For instance, Admin\UserController with index method => will have the permission `admin-user-index`.

## Installation

You can install the package via composer:

```bash
composer require jefremassingue/spatie-permission-generate
```

## Usage

Before using the package, you need to install and configure spatie/laravel-permission (https://github.com/spatie/laravel-permission).
``` php
// Run synchronizelPermission method to generate permissions
    $hasGenarate = \Jefre\SpatiePermissionGenerate\SpatiePermissionGenerate::synchronizelPermission();

```

``` php
// Change the guard name for permissions with a given prefix
    $numPermissionsUpdated = \Jefre\SpatiePermissionGenerate\SpatiePermissionGenerate::changeGuardWithPrefix('api-', 'api');
    
```
### Instalation in Laravel

``` php
// Set the following keys in your `.env` file:

SPG_CONTROLLERS_ROOT_PATH='app/Http/Controllers'
SPG_IGNORE_CLASSES_FILES='Controller,Helper\Upload, Other classes you want ignore'
SPG_CONTROLLER_CLASSES_SUFFIX='Controller,_controller'
SPG_IGNORE_METHODS_AND_FUNCTIONS='__construct'
SPG_DEFAULT_GUARD='web'

```
<table><thead><tr><th>Key</th><th>Description</th><th>Example</th></tr></thead><tbody><tr><td><code>SPG_CONTROLLERS_ROOT_PATH</code></td><td>The root path where the controllers are located. You have to use `/`</td><td><code>app/Http/Controllers</code></td></tr><tr><td><code>SPG_IGNORE_CLASSES_FILES</code></td><td>The classes that should be ignored (separated by comma). You have to use `\`</td><td><code>'Controller,Admin\PermissionGeneratorController,Helper\Upload'</code></td></tr><tr><td><code>SPG_CONTROLLER_CLASSES_SUFFIX</code></td><td>The suffixes for the controller classes.</td><td><code>'_controller,Controller'</code></td></tr><tr><td><code>SPG_IGNORE_METHODS_AND_FUNCTIONS</code></td><td>The methods and functions that should be ignored (separated by comma).</td><td><code>'__construct,pay'</code></td></tr><tr><td><code>SPG_DEFAULT_GUARD</code></td><td>The default guard.</td><td><code>'web'</code></td></tr></tbody></table>


### Testing

To run the tests, execute the following command:
``` bash
composer test
```

### Example

Consider a file located in `/app/Http/Controllers/API/UserController.php`:

``` php
<?php

namespace App\Http\Controllers\API;

...

class UserController extends Controller
{
    public function __construct() {}
    public function index(){}
    public function create(){}
    public function store(Request $request){}
    public function show($id){}
    public function edit($id){}
    public function update(Request $request, $id){}
    public function destroy($id){}
}

```
The generated permissions would be:

`api-user-index`
`api-user-create`
`api-user-store`
`api-user-show`
`api-user-edit`
`api-user-update`
`api-user-destroy`

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email jefremassingue.jm@gmail.com instead of using the issue tracker.

## Credits

- [Jefre Massingue](https://github.com/jefremassingue)
- [Abino Mateve](https://github.com/Albinomateve)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
