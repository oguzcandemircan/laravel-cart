<?php

namespace OguzcanDemircan\LaravelCart\Events;

class CartItemRemoved
{
    /** @var Illuminate\Database\Eloquent\Model */
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}
