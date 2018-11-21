<?php

namespace App\Model\Admin\Place;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $fillable = ['name', 'slug'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function places()
    {
        return $this->morphedByMany('App\Model\Admin\Place\Place', 'taggable');
    }

    public function articles()
    {
        return $this->morphedByMany('App\Model\Admin\Article\Article', 'taggable');
    }
}
