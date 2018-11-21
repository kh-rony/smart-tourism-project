<?php

namespace App\Model\Admin\Hotel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    //
    /*use SoftDeletes;

    protected $dates = ['deleted_at'];*/

    protected $fillable = [
        'name', 'slug', 'email', 'website', 'phone', 'address1', 'address2', 'address3', 'zip_code', 'city', 'state',
        'country', 'latitude', 'longitude', 'total_rooms', 'top_amenities', 'hotel_amenities', 'room_amenities','type_id'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function rooms()
    {
        return $this->belongsToMany('App\Model\Admin\Hotel\Room')->withTimestamps();
    }

    public function type()
    {
        return $this->belongsTo('App\Model\Admin\Hotel\Type');
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
