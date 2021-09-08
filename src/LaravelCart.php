<?php

namespace OguzcanDemircan\LaravelCart;

use OguzcanDemircan\LaravelCart\Contract\CartStorage;

class LaravelCart
{
    public function __construct(CartStorage $driver)
    {
        $this->model = null;
        $this->driver = $driver;
        $this->item = new LaravelCartItem();
        // $this->session = session(config('cart.session_name'));
    }

    public function model($model, $id = null)
    {
        if (is_string($model)) {
            $this->model = $model::findOrFail($id);
        }
        if (is_object($model)) {
            $this->model = $model;
        }
        $this->driver->setModel($model);
        return $this;
    }

    public function price($price)
    {
        $this->item->price($price);
        return $this;
    }

    public function quantity($quantity)
    {
        $this->item->quantity($quantity);
        return $this;
    }

    public function options($options)
    {
        $this->item->options($options);
        return $this;
    }

    public function item()
    {
        return $this->item;
    }

    public function items()
    {
        return $this->driver->items();
    }

    public function remove()
    {
        return $this->driver->remove($this->item);
    }

    public function clear()
    {
        return $this->driver->clear();
    }

    public function add()
    {
        return $this->driver->add($this->item);
    }

    // public function toArray()
    // {
    //     return $this->item->toArray();
    // }
}
