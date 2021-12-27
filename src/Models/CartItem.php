<?php

namespace OguzcanDemircan\LaravelCart\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cart_id', 'model_type', 'model_id', 'name', 'price', 'image', 'quantity', 'note', 'sender_name', 'recipient_name',
    ];

    /**
     * This method is put to convert snake case in camelCase.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'modelType' => $this->model_type,
            'modelId' => $this->model_id,
            'name' => $this->name,
            'price' => $this->price,
            'image' => $this->image,
            'quantity' => $this->quantity,
            'note' => $this->note,
            'sender_name' => $this->sender_name,
            'recipient_name' => $this->recipient_name,
        ];
    }
}
