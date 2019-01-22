<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listener extends Model
{
    /**
     * @var array
     * 'listener'
     */
    protected $fillable = [
        'event', 'listener'
    ];
}
