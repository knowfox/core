# Knowfox. Personal Knowledge Management

This is a package for the Laravel framework

## Installation

To install Knowfox, you will need a working installation of Laravel 7.x.

You can install this prerequisite, using Composer, like so:

````
composer create-project laravel/laravel knowfox
cd knowfox
````

Now, follow the [installation instructions](https://laravel.com/docs/7.x/passport#installation) for Passport.

## Manual installation in packages

During early development, we use a local copy of the Knowfox package in the directory packages/knowfox/core, obtained through a Git submodule.

````
git submodule update
````

1. Run database migrations

````
php artisan migrate
````

2. Access the Knowfox API at `http://knowfox.test/api`

Assuming you have installed [Valet](https://laravel.com/docs/7.x/valet) or a similar development environment.
