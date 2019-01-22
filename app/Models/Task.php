<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'file_id', 'task_id','code', 'code_desc',  'message'
    ];

    function video(){
        return $this->belongsTo(Video::class,'file_id','file_id')->withDefault();
    }
}