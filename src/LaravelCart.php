<?php

namespace OguzcanDemircan\LaravelCart;

class LaravelCart
{
    public function __construct($session)
    {
        $this->session = session(config('laravel-cart.session_name'));
    }

    public function item()
    {
        $this->item = new LaravelCartItem;
        return $this;
    }

    public function add()
    {
    }

    public function save()
    {
        return $this->item->save();
    }
}
