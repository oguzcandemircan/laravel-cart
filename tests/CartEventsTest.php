<?php

namespace OguzcanDemircan\LaravelCart\Test;

use OguzcanDemircan\LaravelCart\Events\CartCleared;
use OguzcanDemircan\LaravelCart\Events\CartCreated;
use OguzcanDemircan\LaravelCart\Events\CartItemAdded;
use OguzcanDemircan\LaravelCart\Events\CartItemRemoved;
use OguzcanDemircan\LaravelCart\Events\DiscountApplied;
use OguzcanDemircan\LaravelCart\Test\Support\TestProduct;
use Illuminate\Support\Facades\Event;

class CartEventsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setup();

        Event::fake();
    }

    /** @test */
    public function fire_cart_created_event()
    {
        $cart = $this->addACartItem();

        Event::assertDispatched(CartCreated::class, function ($e) use ($cart) {
            return $e->cartData['subtotal'] === $cart['subtotal'];
        });
    }

    /** @test */
    public function fire_cart_item_added_event()
    {
        $testProduct = factory(TestProduct::class)->create();

        TestProduct::addToCart($testProduct->id);

        Event::assertDispatched(CartItemAdded::class, function ($e) use ($testProduct) {
            return $e->entity->id === $testProduct->id;
        });
    }

    /** @test */
    public function fire_cart_item_removed_event()
    {
        $testProduct = factory(TestProduct::class)->create();
        TestProduct::addToCart($testProduct->id);

        cart()->removeAt(0);

        Event::assertDispatched(CartItemRemoved::class, function ($e) use ($testProduct) {
            return $e->entity->id === $testProduct->id;
        });
    }

    /** @test */
    public function fire_cart_cleared_event()
    {
        $this->addACartItem();

        cart()->clear();

        Event::assertDispatched(CartCleared::class);
    }

    /** @test */
    public function fire_discount_applied_event()
    {
        $this->addACartItem($quantity = 1, [
            'price' => 100,
        ]);

        $cart = cart()->applyDiscount(10);

        Event::assertDispatched(DiscountApplied::class, function ($e) use ($cart) {
            return $e->cartData['discount'] === $cart['discount'];
        });

        $cart = cart()->applyFlatDiscount(10);

        Event::assertDispatched(DiscountApplied::class, function ($e) use ($cart) {
            return $e->cartData['discount'] === $cart['discount'];
        });
    }
}
