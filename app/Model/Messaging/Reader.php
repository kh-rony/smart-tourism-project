<?php

namespace App\Model\Messaging;

use Illuminate\Database\Eloquent\Model;

class Reader extends Model
{
    protected $fillable = ['user_id'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}
