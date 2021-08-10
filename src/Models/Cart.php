<?php

namespace OguzcanDemircan\LaravelCart\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $casts = [
        'options' => 'array',
    ];

    protected $guarded = [];

    public function buyable()
    {
        return $this->morphTo();
    }
}
