<?php

namespace OguzcanDemircan\LaravelCart\Driver;

use OguzcanDemircan\LaravelCart\Contract\CartStorage;

class DatabaseStorageDriver implements CartStorage
{
    public $model;

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function setCartModel($cartModel)
    {
        $this->cartModel = $cartModel;

        return $this;
    }

    public function getQuery()
    {
        return $this->cartModel->where('user_id', $this->getId());
    }

    public function get()
    {
        return $this->getQuery()->get();
    }

    public function store($data)
    {
        return $this->getQuery()->create($data);
    }

    public function update($data)
    {
        return  $this->getQuery()
            ->where($this->getMorphData())
            ->update($data);
    }

    public function add($item)
    {
        $data = array_merge($item->toArray(), $this->getMorphData());

        return $this->cartModel->create($data);
    }

    public function remove($model)
    {
        return $this->getQuery()->where($this->getMorphData())->delete();
    }

    public function clear()
    {
        return $this->getQuery()->delete();
    }

    public function count()
    {
        return $this->getQuery()->count();
    }

    public function items()
    {
        return $this->getQuery()->get();
    }

    public function countWithQuantity()
    {
        $items = $this->items()->map(function ($value) {
            dd($value);
        });
    }

    public function getMorphData()
    {
        return [
            'cartable_id' => $this->model->id,
            'cartable_type' => get_class($this->model),
            'user_id' => $this->getId(),
        ];
    }

    public function setId(int | string $id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
}
