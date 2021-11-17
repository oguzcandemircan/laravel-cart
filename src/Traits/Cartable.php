<?php

namespace OguzcanDemircan\LaravelCart\Traits;

use OguzcanDemircan\LaravelCart\Core\Cart;

trait Cartable
{
    /**
     * Adds an item to the cart.
     *
     * @param int Identifier
     * @param int quantity
     * @return
     */
    public static function addToCart(int $id, int $quantity = 1, string $note = null)
    {
        $class = static::class;

        return app(Cart::class)->add($class::findOrFail($id), $quantity, $note);
    }
}
