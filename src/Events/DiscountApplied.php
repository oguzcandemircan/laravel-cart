<?php

namespace OguzcanDemircan\LaravelCart\Events;

class DiscountApplied
{
    /** @var array */
    public $cartData;

    public function __construct($cartData)
    {
        $this->cartData = $cartData;
    }
}
