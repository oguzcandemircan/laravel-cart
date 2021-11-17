<?php

namespace OguzcanDemircan\LaravelCart\Test\Support;

use Illuminate\Database\Eloquent\Model;
use OguzcanDemircan\LaravelCart\Traits\Cartable;

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
