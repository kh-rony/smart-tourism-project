<?php

namespace App\Model\Admin\Hotel;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    protected $fillable = ['name', 'slug'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function hotels()
    {
        return $this->belongsToMany('App\Model\Admin\Hotel\Hotel')->withTimestamps();
    }
}
