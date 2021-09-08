<?php

use OguzcanDemircan\LaravelCart\LaravelCart;

if (!function_exists('cart')) {
    /**
     * Return instance of the Cart class.
     */
    function cart()
    {
        return app(LaravelCart::class);
    }
}
