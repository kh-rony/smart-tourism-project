<?php

namespace App\Model\Admin\Hotel;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
    protected $fillable = ['name', 'slug'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function hotels()
    {
        return $this->hasMany('App\Model\Hotel\Hotel');
    }
}
