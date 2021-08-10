<?php

namespace OguzcanDemircan\LaravelCart;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use OguzcanDemircan\LaravelCart\Commands\LaravelCartCommand;

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
}