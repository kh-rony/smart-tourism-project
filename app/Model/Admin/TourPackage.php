<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class TourPackage extends Model
{
    use HasSlug;

    protected $fillable = [
        'slug', 'name', 'duration', 'origin', 'destination', 'transport', 'tax', 'accommodation', 'breakfast', 'lunch', 'dinner',
        'sight_seeing', 'guide_service', 'plans', 'token', 'price'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['name'])
            ->saveSlugsTo('slug');
    }

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

    public function description()
    {
        return $this->morphOne('App\Model\Admin\Description', 'descriptable');
    }

    public function admin()
    {
        return $this->belongsTo('App\Model\Admin\Admin');
    }
}
