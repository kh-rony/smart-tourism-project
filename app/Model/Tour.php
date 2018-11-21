<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    public $fillable = ['name', 'description', 'user_id'];

    protected $hidden = ['user_id', 'pivot'];

    public function users()
    {
        return $this->belongsToMany('App\Model\User')->withTimestamps();
    }

    public function mates()
    {
        return $this->belongsToMany('App\Model\User')->withPivot('person', 'relation')
            ->wherePivot('tour_id', '=', $this->id)
            ->wherePivot('going', '=', 2);
    }

    public function notGoingMates()
    {
        return $this->belongsToMany('App\Model\User')
            ->wherePivot('tour_id', '=', $this->id)
            ->wherePivot('going', '=', 1)
            ->wherePivot('relation', '=', 1);
    }

    public function invitedMates()
    {
        return $this->belongsToMany('App\Model\User')
            ->wherePivot('tour_id', '=', $this->id)
            ->wherePivot('going', '=', 0)
            ->wherePivot('relation', '=', 1);
    }

    public function requestedPublic()
    {
        return $this->belongsToMany('App\Model\User')->withPivot('person', 'going')
            ->wherePivot('tour_id', '=', $this->id)
            ->wherePivot('relation', '=', 0)
            ->wherePivot('going', '<', 2);
    }
}
