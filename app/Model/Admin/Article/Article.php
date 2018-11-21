<?php

namespace App\Model\Admin\Article;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //
    protected $fillable = [
        'title', 'content', 'slug'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function categories()
    {
        return $this->morphToMany('App\Model\Admin\Place\Category', 'categorable');
    }

    public function tags()
    {
        return $this->morphToMany('App\Model\Admin\Place\Tag', 'taggable');
    }

    public function photos()
    {
        return $this->morphMany('App\Model\Admin\Photo', 'imageable');
    }

    public function admin()
    {
        return $this->belongsTo('App\Model\Admin\Admin');
    }
}
