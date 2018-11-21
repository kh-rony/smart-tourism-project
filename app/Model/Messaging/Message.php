<?php

namespace App\Model\Messaging;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['body'];
    protected $touches = ['conversation'];

    public function conversation()
    {
        return $this->belongsTo('App\Model\Messaging\Conversation');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

    public function readers()
    {
        return $this->hasMany('App\Model\Messaging\Reader')->with('user');
    }

    public function photos()
    {
        return $this->morphMany('App\Model\Admin\Photo', 'imageable');
    }
}
