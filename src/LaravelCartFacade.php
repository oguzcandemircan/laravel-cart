<?php

namespace OguzcanDemircan\LaravelCart;

use Illuminate\Support\Facades\Facade;

/**
 * @see \OguzcanDemircan\LaravelCart\LaravelCart
 */
class LaravelCartFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-cart';
    }
}
