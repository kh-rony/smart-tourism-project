<?php

namespace App\Model\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    //
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'status', 'role_id', 'gender', 'image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setNameAttribute($name)
    {
        $this->attributes['name'] =  ucfirst($name);
    }

    public function role()
    {
        return $this->belongsTo('App\Model\Admin\Role');
    }

    public function places()
    {
        return $this->hasMany('App\Model\Admin\Place\Place');
    }

    public function placeTags()
    {
        return $this->hasMany('App\Model\Admin\Place\Tag');
    }

    public function placeCategories()
    {
        return $this->hasMany('App\Model\Admin\Place\Category');
    }

    public function hotels()
    {
        return $this->hasMany('App\Model\Admin\Hotel\Hotel');
    }

    public function articles()
    {
        return $this->hasMany('App\Model\Admin\Article\Article');
    }

    public function hotelTypes()
    {
        return $this->hasMany('App\Model\Admin\Hotel\Type');
    }

    public function hotelRoomTypes()
    {
        return $this->hasMany('App\Model\Admin\Hotel\RoomType');
    }

    public function tourPackages()
    {
        return $this->hasMany('App\Model\Admin\TourPackage');
    }

}
