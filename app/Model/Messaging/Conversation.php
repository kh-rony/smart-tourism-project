<?php

namespace App\Model\Messaging;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany('App\Model\User')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany('App\Model\Messaging\Message');
    }

    public function readers()
    {
        return $this->hasManyThrough('App\Model\Messaging\Reader', 'App\Model\Messaging\Message');
    }
}
