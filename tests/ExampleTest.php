<?php

namespace OguzcanDemircan\LaravelCart\Tests;

class ExampleTest extends TestCase
{
    public function test_it_can_add_item_on_cart()
    {
        $this->actingAs($this->user);
        $quantity = 2;
        $options = ['size' => 'large', 'color' => 'red'];

        $cart = $this->laravelCart->model($this->product)
            ->price($this->product->price)
            ->quantity($quantity)
            ->options($options)
            ->add();

        $result = $cart->only('id', 'user_id', 'price', 'quantity', 'options', 'cartable_id', 'cartable_type');
        $this->assertEquals($result, [
            "id" => $cart->id,
            "user_id" => $this->user->id,
            "price" => 100.0,
            "quantity" => $quantity,
            "options" => $options,
            "cartable_id" => $cart->cartable->id,
            "cartable_type" => get_class($cart->cartable),
        ]);

        return $cart;
    }

    /** @test */
    public function test_it_can_remove_item_on_cart()
    {
        $cart = $this->test_it_can_add_item_on_cart();
        $bool = $this->laravelCart->model($this->product)->remove($cart["id"]);
        $this->assertEquals($bool, true);
    }

    public function test_it_can_clear_item_on_cart()
    {
        $cart1 = $this->test_it_can_add_item_on_cart();
        $cart2 = $this->test_it_can_add_item_on_cart();
        $this->laravelCart->clear();
    }
}
