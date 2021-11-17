<?php

namespace OguzcanDemircan\LaravelCart\Observers;

use OguzcanDemircan\LaravelCart\Models\Cart;

class CartObserver
{
    /**
     * Listen to the Cart deleting event.
     *
     * @param \OguzcanDemircan\LaravelCart\Models\Cart $cart
     * @return void
     */
    public function deleting(Cart $cart)
    {
        $cart->items()->delete();
    }
}
