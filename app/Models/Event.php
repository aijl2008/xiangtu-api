<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'version', 'type', 'code', 'status', 'message', 'data'
    ];
}
