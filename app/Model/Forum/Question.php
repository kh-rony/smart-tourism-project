<?php

namespace App\Model\Forum;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['title', 'body'];

    public function replies()
    {
        return $this->morphMany('App\Model\Forum\Reply', 'replyable');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

    public function photos()
    {
        return $this->morphMany('App\Model\Admin\Photo', 'imageable');
    }
}
