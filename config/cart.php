<?php
// config for OguzcanDemircan/LaravelCart
return [
    'session_name' => 'LaravelCart',

    'model_identity_column' => 'id',
    'model_price_column' => 'price',
    'model_quantity_column' => 'quantity',

    'driver' => \OguzcanDemircan\LaravelCart\Driver\DatabaseStorageDriver::class,

    'model' => \OguzcanDemircan\LaravelCart\Models\Cart::class,
];
