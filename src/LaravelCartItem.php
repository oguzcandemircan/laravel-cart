<?php

namespace OguzcanDemircan\LaravelCart;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class LaravelCartItem implements Arrayable, Jsonable
{
    protected $price;

    protected $quantity;

    protected $options;

    protected $user;

    public function __construct()
    {
        $this->price = 0;
        $this->quantity = 0;
        $this->options = [];
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

    public function options(array $options): self
    {
        $this->options = $options;

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

    public function toArray($id = null): array
    {
        return [
            'user_id' => $id,
            'price' => $this->getPrice(),
            'quantity' => $this->getQuantity(),
            'options' => $this->getOptions(),
        ];
    }

    public function toJson($options = 0)
    {
        return  json_encode($this->toArray(), $options);
    }
}
