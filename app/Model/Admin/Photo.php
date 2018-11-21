<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    //
    protected $fillable = ['img_url'];

    public function imageable()
    {
        return $this->morphTo();
    }

}
