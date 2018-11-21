<?php

namespace App\Model\Forum;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = ['body'];

    public function replyable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

    public function photos()
    {
        return $this->morphMany('App\Model\Admin\Photo', 'imageable');
    }

    public function replies()
    {
        return $this->morphMany('App\Model\Forum\Reply', 'replyable');
    }

}
