# Knowfox. Personal Knowledge Management

This is a package for the Laravel framework

## Installation

To install Knowfox, you will need a working installation of Laravel 6.x, includeing the Passport package.

You can install these prerequisites, using Composer, like so:

````
composer create-project laravel/laravel knowfox
cd knowfox
composer require laravel/passport
````

Now, follow the [installation instructions](https://laravel.com/docs/6.x/passport#installation) for Passport.

## Manual installation in packages

During early development, we use a local copy of the Knowfox package in the directory packages/knowfox/core. You can obtain a copy by cloning the repository:

````
git clone ssh://gogs@code.schettler.net:8222/olav/knowfox-core.git
````

Please ask the author at olav@schettler.net for access to this repository.

To register the package with the framework, you need to follow these steps:

1. Install the prerequisites

````
composer require barryvdh/laravel-cors cebe/markdown kalnoy/nestedset mpociot/versionable rtconner/laravel-tagging doctrine/dbal guzzlehttp/guzzle ramsey/uuid symfony/yaml

php artisan migrate --path=vendor/mpociot/versionable/src/migrations/2014_09_27_212641_create_versions_table.php
````

2. Register the Knowfox\Core namespace by editing `composer.json`:

````
"psr-4": {
    "App\\": "app/",
    "Knowfox\\Core\\": "packages/knowfox/core/src/"
}
````

Flush the autoload registry

````
composer dump-autoload
````

3. Register the ServiceProvider in `config/app.php`:

````
/*
 * Package Service Providers...
 */
Knowfox\Core\ServiceProvider::class,
````

4. Run database migrations

````
php artisan migrate
````

5. Access the Knowfox API at `http://knowfox.test/api`

Assuming you have installed [Valet](https://laravel.com/docs/6.x/valet) or a similar development environment.
