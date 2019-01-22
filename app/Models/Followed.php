<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Followed extends Model
{
    protected $table = "wechats";

    function video()
    {
        return $this->hasMany(Video::class, 'wechat_id');
    }
}
