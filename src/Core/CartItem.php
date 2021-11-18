<?php

namespace OguzcanDemircan\LaravelCart\Core;

use Illuminate\Contracts\Support\Arrayable;
use OguzcanDemircan\LaravelCart\Exceptions\ItemNameMissing;
use OguzcanDemircan\LaravelCart\Exceptions\ItemPriceMissing;

class CartItem implements Arrayable
{
    public $id = null;

    public $modelType;

    public $modelId;

    public $name;

    public $price;

    public $image = null;

    public $quantity = 1;

    public $note = null;

    /**
     * Creates a new cart item.
     *
     * @param Illuminate\Database\Eloquent\Model|array
     * @param int Quantity of the item
     * @return \OguzcanDemircan\LaravelCart\Core\CartItem
     */
    public function __construct($data, int $quantity, $note = null)
    {
        if (is_array($data)) {
            return $this->createFromArray($data);
        }

        return $this->createFromModel($data, $quantity, $note);
    }

    /**
     * Creates a new cart item from a model instance.
     *
     * @param Illuminate\Database\Eloquent\Model
     * @param int Quantity of the item
     * @return \OguzcanDemircan\LaravelCart\Core\CartItem
     */
    protected function createFromModel($entity, $quantity, $note = null)
    {
        $this->modelType = get_class($entity);
        $this->modelId = $entity->{$entity->getKeyName()};
        $this->setName($entity);
        $this->setPrice($entity);
        $this->setImage($entity);
        $this->quantity = $quantity;
        $this->note = $note;

        return $this;
    }

    /**
     * Creates a new cart item from an array.
     *
     * @param array
     * @return \OguzcanDemircan\LaravelCart\Core\CartItem
     */
    protected function createFromArray($array)
    {
        $this->id = $array['id'];
        $this->modelType = $array['modelType'];
        $this->modelId = $array['modelId'];
        $this->name = $array['name'];
        $this->price = $array['price'];
        $this->image = $array['image'];
        $this->quantity = $array['quantity'];
        $this->note = $array['note'];

        return $this;
    }

    /**
     * Creates a new cart item from an array or entity.
     *
     * @param Illuminate\Database\Eloquent\Model|array
     * @param int Quantity of the item
     * @return \OguzcanDemircan\LaravelCart\Core\CartItem
     */
    public static function createFrom($data, $quantity = 1, $note = null)
    {
        return new static($data, $quantity, $note);
    }

    /**
     * Sets the name of the item.
     *
     * @param Illuminate\Database\Eloquent\Model
     * @return void
     * @throws ItemNameMissing
     */
    protected function setName($entity)
    {
        if (method_exists($entity, 'getName')) {
            $this->name = $entity->getName();

            return;
        }

        if ($entity->offsetExists('name')) {
            $this->name = $entity->name;

            return;
        }

        throw ItemNameMissing::for($this->modelType);
    }

    /**
     * Sets the price of the item.
     *
     * @param Illuminate\Database\Eloquent\Model
     * @return void
     * @throws ItemPriceMissing
     */
    protected function setPrice($entity)
    {
        if (method_exists($entity, 'getPrice')) {
            $this->price = $entity->getPrice();

            return;
        }

        if ($entity->offsetExists('price')) {
            $this->price = $entity->price;

            return;
        }

        throw ItemPriceMissing::for($this->modelType);
    }

    /**
     * Sets the image of the item.
     *
     * @param Illuminate\Database\Eloquent\Model
     * @return void
     */
    protected function setImage($entity)
    {
        if (method_exists($entity, 'getImage')) {
            $this->image = $entity->getImage();

            return;
        }

        if (isset($entity->image)) {
            $this->image = $entity->image;
        }
    }

    /**
     * Returns object properties as array.
     *
     * @return array
     */
    public function toArray()
    {
        $cartItemData = [
            'modelType' => $this->modelType,
            'modelId' => $this->modelId,
            'name' => $this->name,
            'price' => $this->price,
            'image' => $this->image,
            'quantity' => $this->quantity,
            'note' => $this->note,
        ];

        if ($this->id) {
            $cartItemData['id'] = $this->id;
        }

        return $cartItemData;
    }
}
