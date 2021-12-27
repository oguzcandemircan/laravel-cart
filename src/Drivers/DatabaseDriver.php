<?php

namespace OguzcanDemircan\LaravelCart\Drivers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use OguzcanDemircan\LaravelCart\Contracts\CartDriver;
use OguzcanDemircan\LaravelCart\Models\Cart;
use OguzcanDemircan\LaravelCart\Models\CartItem;

class DatabaseDriver implements CartDriver
{
    /**
     * Returns current cart data.
     *
     * @return array
     */
    public function getCartData()
    {
        $selectColumns = ['id', 'subtotal', 'discount', 'discount_percentage', 'coupon_id', 'shipping_charges', 'net_total', 'tax', 'total', 'round_off', 'payable'];

        $cartData = Cart::with($this->cartItemsQuery())
            ->where($this->cartIdentifier())
            ->first($selectColumns)
        ;

        // If there is no cart record for the logged in customer, try with cookie identifier
        if (! $cartData && Auth::guard(config('cart_manager.auth_guard'))->check()) {
            $cartData = Cart::with($this->cartItemsQuery())
                ->where($this->getCookieElement())
                ->first($selectColumns)
            ;

            if ($cartData) {
                $this->assignCustomerToCartRecord();
            }
        }

        if (! $cartData) {
            return [];
        }

        return $cartData->toArray();
    }

    /**
     * Returns the query to fetch cart items.
     *
     * @return array
     */
    protected function cartItemsQuery()
    {
        return [
            'items' => function ($query) {
                $query->select('id', 'cart_id', 'model_type', 'model_id', 'name', 'price', 'image', 'quantity', 'note')
                    ->orderBy('id', 'asc')
                ;
            },
        ];
    }

    /**
     * Assigns the customer to the cart record identified by cookie.
     *
     * @return void
     */
    protected function assignCustomerToCartRecord()
    {
        // Assign the logged in customer to the cart record
        Cart::where($this->getCookieElement())->update([
            'auth_user' => Auth::guard(config('cart_manager.auth_guard'))->id(),
        ]);
    }

    /**
     * Stores the cart and cart items data in the database tables.
     *
     * @param array Cart data
     * @return void
     */
    public function storeNewCartData($cartData)
    {
        $cartItems = $cartData['items'];
        unset($cartData['items']);
        $cartId = $this->storeCartDetails($cartData);

        foreach ($cartItems as $cartItem) {
            $this->addCartItem($cartId, $cartItem);
        }
    }

    /**
     * Updates the cart record with the new data.
     *
     * @param int Id of the cart
     * @param array Cart data
     * @return void
     */
    public function updateCart($cartId, $cartData)
    {
        $cartData = $this->arraySnakeCase($cartData);

        Cart::where('id', $cartId)->update($cartData);
    }

    /**
     * Add a new cart item to the database.
     *
     * @param int Cart id
     * @param array Cart item data
     * @return void
     */
    public function addCartItem($cartId, $cartItem)
    {
        $cartItem = $this->arraySnakeCase($cartItem);

        $cartItem['cart_id'] = $cartId;
        CartItem::create($cartItem);
    }

    /**
     * Removes a cart item from the database.
     *
     * @param int Cart item id
     * @return void
     */
    public function removeCartItem($cartItemId)
    {
        CartItem::destroy($cartItemId);
    }

    /**
     * Stores the cart data in the database table and returns the id of the record.
     *
     * @param array Cart data
     * @return int
     */
    protected function storeCartDetails($cartData)
    {
        $cartData = $this->arraySnakeCase($cartData);

        $cart = Cart::updateOrCreate(
            $this->cartIdentifier(),
            array_merge($cartData, $this->getCookieElement())
        );

        return $cart->id;
    }

    /**
     * Returns the cart identifier.
     *
     * @return array
     */
    protected function cartIdentifier()
    {
        // If auth user is set manually, use it
        if (app()->offsetExists('cart_auth_user_id')) {
            return ['auth_user' => resolve('cart_auth_user_id')];
        }

        // If customer is logged in, use his identifier
        if (Auth::guard(config('cart_manager.auth_guard'))->check()) {
            return ['auth_user' => Auth::guard(config('cart_manager.auth_guard'))->id()];
        }

        return $this->getCookieElement();
    }

    /**
     * Returns the cookie for the cart identification.
     *
     * @return array
     */
    protected function getCookieElement()
    {
        if (! request()->hasCookie(config('cart_manager.cookie_name'))) {
            $cookie = Str::random(20);

            Cookie::queue(Cookie::make(
                config('cart_manager.cookie_name'),
                $cookie,
                config('cart_manager.cookie_lifetime')
            ));
        } else {
            $cookie = Cookie::get(config('cart_manager.cookie_name'));
        }

        return ['cookie' => $cookie];
    }

    /**
     * Updates the quantity in the cart items table.
     *
     * @param int Id of the cart item
     * @param int New quantity to be set
     * @return void
     */
    public function setCartItemQuantity($cartItemId, $newQuantity)
    {
        CartItem::where('id', $cartItemId)->update(['quantity' => $newQuantity]);
    }

    public function setCartItemNote($cartItemId, $note)
    {
        CartItem::where('id', $cartItemId)->update(['note' => $note]);
    }

    
    public function setCartItemSender($cartItemId, $sender)
    {
        CartItem::where('id', $cartItemId)->update([
            'sender_name' => $sender,
        ]);
    }

    public function setCartItemRecipient($cartItemId, $recipient)
    {
        CartItem::where('id', $cartItemId)->update([
            'recipient_name' => $recipient
        ]);
    }

    /**
     * Clears the cart details including cart items from the database.
     *
     * @return void
     */
    public function clearData()
    {
        $cart = Cart::where($this->cartIdentifier())->first();

        if ($cart) {
            $cart->delete();
        }
    }

    /**
     * Converts the keys of an array into snake case.
     *
     * @param array
     * @return array
     */
    private function arraySnakeCase($array)
    {
        $newArray = [];

        foreach ($array as $key => $value) {
            $newArray[Str::snake($key)] = $value;
        }

        return $newArray;
    }

    /**
     * Updates the details in the cart items table.
     *
     * @param \Illuminate\Support\Collection Cart items
     * @return void
     */
    public function updateItemsData($items)
    {
        $items->each(function ($item) {
            CartItem::where('id', $item->id)->update([
                'name' => $item->name,
                'price' => $item->price,
                'image' => $item->image,
            ]);
        });
    }
}
