<?php

namespace OguzcanDemircan\LaravelCart\Test\Support;

use OguzcanDemircan\LaravelCart\Traits\Cartable;
use Illuminate\Database\Eloquent\Model;

class TestProduct extends Model
{
    use Cartable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['price'];
}
