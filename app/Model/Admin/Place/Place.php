<?php

namespace App\Model\Admin\Place;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Place extends Model
{
    use Searchable;

//    use SoftDeletes;
//
//    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'slug', 'address1', 'address2', 'address3', 'zip_code', 'city', 'state', 'country',
        'latitude', 'longitude', 'website', 'weekly_holiday', 'start_hour', 'end_hour', 'price',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function searchableAs()
    {
        return 'places_index';
    }

    public function getScoutKey()
    {
        return $this->slug;
    }

    public function toSearchableArray()
    {
        $array = $this->only('name', 'address1', 'address2', 'address3', 'city', 'state', 'country');

        // Customize array...
        $array['id'] = $this->id;

        return $array;
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

    public function description()
    {
        return $this->morphOne('App\Model\Admin\Description', 'descriptable');
    }

    public function admin()
    {
        return $this->belongsTo('App\Model\Admin\Admin');
    }
}
