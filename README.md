# laravel-aerospike

A service to use Aerospike Cache on Laravel 5

## Installation

### 1. Dependency

Using <a href="https://getcomposer.org/" target="_blank">composer</a>, execute the following command to automatically update your `composer.json`:

```shell
composer require luciano-jr/laravel-aerospike
```

or manually update your `composer.json` file

```json
{
	"require": {
		"luciano-jr/laravel-aerospike": "dev-master"
	}
}
```

### 2. Provider

You need to update your application configuration in order to register the package, so it can be loaded by Laravel. Just update your `config/app.php` file adding the following code at the end of your `'providers'` section:

```php
// file START ommited
    'providers' => [
        // other providers ommited
        Lucianojr\Aerospike\Providers\AerospikeServiceProvider::class,
    ],
// file END ommited
```

#### 2.1 Publishing configuration file and migrations

To publish the default configuration file and database migrations, execute the following command: 

```shell
php artisan vendor:publish
```

You can also publish only the configuration file or the migrations:

```shell
php artisan vendor:publish --tag=config
```
Or
```shell
php artisan vendor:publish --tag=migrations
```

If you already published defender files, but for some reason you want to override previous published files, add the `--force` flag.

### 3. Facade (optional)
In order to use the `Defender` facade, you need to register it on the `config/app.php` file, you can do that the following way:

```php
// config.php file
// file START ommited
    'aliases' => [
        // other Facades ommited
        'AerospikeCache' => Lucianojr\Aerospike\Facades\AerospikeFacade::class,
    ],
// file END ommited
```