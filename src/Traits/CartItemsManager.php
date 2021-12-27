<?php

namespace OguzcanDemircan\LaravelCart\Traits;

use OguzcanDemircan\LaravelCart\Core\CartItem;
use OguzcanDemircan\LaravelCart\Events\CartItemAdded;
use OguzcanDemircan\LaravelCart\Events\CartItemRemoved;
use OguzcanDemircan\LaravelCart\Exceptions\ItemMissing;

trait CartItemsManager
{
    /**
     * Adds an item to the cart.
     *
     * @param Illuminate\Database\Eloquent\Model
     * @param int Quantity
     * @return array
     */
    public function add($entity, $quantity, $note = null)
    {
        if ($this->itemExists($entity)) {
            $cartItemIndex = $this->items->search($this->cartItemsCheck($entity));

            $this->updateNote($cartItemIndex, $note);
            $this->updateSenderAndRecipient($cartItemIndex, $entity->sender_name, $entity->recipient_name);

            return $this->incrementQuantityAt($cartItemIndex, $quantity);
        }

        $this->items->push(CartItem::createFrom($entity, $quantity, $note));

        event(new CartItemAdded($entity));

        return $this->cartUpdates($isNewItem = true);
    }

    /**
     * Checks if an item already exists in the cart.
     *
     * @param Illuminate\Database\Eloquent\Model
     * @return bool
     */
    protected function itemExists($entity)
    {
        return $this->items->contains($this->cartItemsCheck($entity));
    }

    /**
     * Checks if a cart item with the specified entity already exists.
     *
     * @param Illuminate\Database\Eloquent\Model
     * @return \Closure
     */
    protected function cartItemsCheck($entity)
    {
        return function ($item) use ($entity) {
            return $item->modelType == get_class($entity) &&
                $item->modelId == $entity->{$entity->getKeyName()}
            ;
        };
    }

    /**
     * Removes an item from the cart.
     *
     * @param int index of the item
     * @return array
     */
    public function removeAt($cartItemIndex)
    {
        $this->existenceCheckFor($cartItemIndex);

        $item = $this->items[$cartItemIndex];

        $this->cartDriver->removeCartItem($item->id);
        $this->items = $this->items->forget($cartItemIndex)->values(); // To reset the index

        $modelType = $item->modelType;
        $entity = $modelType::find($item->modelId);
        event(new CartItemRemoved($entity));

        return $this->cartUpdates();
    }

    /**
     * Throws an exception is the there is no item at the specified index.
     *
     * @param int index of the item
     * @return void
     * @throws ItemMissing
     */
    protected function existenceCheckFor($cartItemIndex)
    {
        if (! $this->items->has($cartItemIndex)) {
            throw new ItemMissing('There is no item in the cart at the specified index.');
        }
    }

    /**
     * Set the quantity of a cart item.
     *
     * @param int Index of the cart item
     * @param int quantity to be increased
     * @return array
     */
    public function setQuantityAt($cartItemIndex, $quantity)
    {
        $this->existenceCheckFor($cartItemIndex);

        $this->items[$cartItemIndex]->quantity = $quantity;

        $this->cartDriver->setCartItemQuantity(
            $this->items[$cartItemIndex]->id,
            $this->items[$cartItemIndex]->quantity
        );

        return $this->cartUpdates();
    }

    /**
     * Increments the quantity of a cart item.
     *
     * @param int Index of the cart item
     * @param int quantity to be increased
     * @return array
     */
    public function incrementQuantityAt($cartItemIndex, $quantity = 1)
    {
        $this->existenceCheckFor($cartItemIndex);

        $this->items[$cartItemIndex]->quantity += $quantity;

        $this->cartDriver->setCartItemQuantity(
            $this->items[$cartItemIndex]->id,
            $this->items[$cartItemIndex]->quantity
        );

        return $this->cartUpdates();
    }

    /**
     * Decrements the quantity of a cart item.
     *
     * @param int Index of the cart item
     * @param int quantity to be decreased
     * @return array
     */
    public function decrementQuantityAt($cartItemIndex, $quantity = 1)
    {
        $this->existenceCheckFor($cartItemIndex);

        if ($this->items[$cartItemIndex]->quantity <= $quantity) {
            return $this->removeAt($cartItemIndex);
        }

        $this->items[$cartItemIndex]->quantity -= $quantity;

        $this->cartDriver->setCartItemQuantity(
            $this->items[$cartItemIndex]->id,
            $this->items[$cartItemIndex]->quantity
        );

        return $this->cartUpdates();
    }

    public function updateNote($cartItemIndex,  $note = null)
    {
        $this->existenceCheckFor($cartItemIndex);

        if ($this->items[$cartItemIndex]->note != $note and $note != null) {
            $this->items[$cartItemIndex]->note = $note;

            $this->cartDriver->setCartItemNote(
                $this->items[$cartItemIndex]->id,
                $this->items[$cartItemIndex]->note
            );
        }

        return $this->cartUpdates();
    }

    public function updateSenderAndRecipient($cartItemIndex, $sender, $recipient)
    {
        $this->existenceCheckFor($cartItemIndex);
        // dd($this->items[$cartItemIndex]);
        if ($this->items[$cartItemIndex]->sender_name != $sender and $sender != null) {
            $this->items[$cartItemIndex]->sender_name = $sender;

            $this->cartDriver->setCartItemSender(
                $this->items[$cartItemIndex]->id,
                $this->items[$cartItemIndex]->sender_name
            );
        }

        if ($this->items[$cartItemIndex]->recipient_name != $recipient and $recipient != null) {
            $this->items[$cartItemIndex]->recipient_name = $recipient;
            $this->cartDriver->setCartItemRecipient(
                $this->items[$cartItemIndex]->id,
                $this->items[$cartItemIndex]->recipient_name
            );
        }

        return $this->cartUpdates();
    }

    /**
     * Refreshes all items data.
     *
     * @return array
     */
    public function refreshAllItemsData()
    {
        $keepDiscount = true;

        $this->items->transform(function ($item) use (&$keepDiscount) {
            $freshEntity = $item->modelType::findOrFail($item->modelId);

            $cartItem = CartItem::createFrom($freshEntity, $item->quantity, $item->note);

            if ($cartItem->price != $item->price) {
                $keepDiscount = false;
            }

            $item->name = $cartItem->name;
            $item->price = $cartItem->price;
            $item->image = $cartItem->image;

            return $item;
        });

        $this->cartDriver->updateItemsData($this->items);

        $this->updateTotals($keepDiscount);
        $this->cartDriver->updateCart($this->id, $this->data());

        return $this->toArray();
    }
}
