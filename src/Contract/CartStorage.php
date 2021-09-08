<?php

namespace OguzcanDemircan\LaravelCart\Contract;

interface CartStorage
{
    /**
     * Return current cart data.
     *
     * @return array Cart data (including items)
     */
    public function get();

    /**
     * Store cart data.
     *
     * @param array Cart data (including items)
     * @return void
     */
    public function store($data);

    /**
     * Update the cart record with the new data.
     *
     * @param int Id of the cart
     * @param array Cart data (without items)
     * @return void
     */
    public function update($data);

    /**
     * Add a new cart item to the cart.
     *
     * @param int Cart id
     * @param array Cart item data
     * @return void
     */
    public function add($data);

    /**
     * Remove a cart item from the cart.
     *
     * @param int Cart item id
     * @return void
     */
    public function remove($id);

    /**
     * Clears all the cart details including cart items.
     *
     * @return void
     */
    public function clear();
}
