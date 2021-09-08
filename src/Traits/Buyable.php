<?php

namespace OguzcanDemircan\LaravelCart\Traits;

use OguzcanDemircan\LaravelCart\Models\Cart;

trait Buyable
{
    public function cartItems()
    {
        return $this->morphMany(Cart::class, 'buyable');
    }
}
