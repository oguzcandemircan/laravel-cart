<?php

namespace OguzcanDemircan\LaravelCart;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

class LaravelCartItem
{
    public function __construct()
    {
        $this->model = null;
        $this->price = 0;
        $this->quantity = 0;
        $this->options = [];
    }

    public function model($model, $id)
    {
        if (is_string($model)) {
            $this->model = (new $model())->find($id);
        }
        if (is_object($model)) {
            $this->model =  $model;
        }

        throw new InvalidArgumentException("Error Processing Request", 1);
    }

    public function price(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function quantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getAttributes()
    {
        return [
            'price' => $this->getPrice(),
            'quantity' => $this->getQuantity(),
            'options' => $this->getOptions(),
        ];
    }

    public function save()
    {
        return $this->getModel()->cartItems()->create($this->item->getAttributes());
    }
}
