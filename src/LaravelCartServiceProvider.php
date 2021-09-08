<?php

namespace OguzcanDemircan\LaravelCart;

use OguzcanDemircan\LaravelCart\Commands\LaravelCartCommand;
use OguzcanDemircan\LaravelCart\Contract\CartStorage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCartServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-cart')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-cart_table')
            ->hasCommand(LaravelCartCommand::class);
    }

    public function register()
    {
        parent::register();
        $this->app->bind(CartStorage::class, function () {
            $model = $this->app['config']['cart']['model'];
            $driver = $this->app['config']['cart']['driver'];

            return (new $driver())
                ->setCartModel(new $model())
                ->setId(1);
        });

        $this->app->bind(LaravelCart::class, function ($app) {
            return new LaravelCart($app->make(CartStorage::class));
        });
    }
}
