<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    //
    protected $fillable = ['body'];

    public function descriptable()
    {
        return $this->morphTo();
    }
}
