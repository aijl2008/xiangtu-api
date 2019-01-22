<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'to_user_name',
        'from_user_name',
        'create_time',
        'msg_type',
        'event',
        'session_from',
        'content',
        'pic_url',
        'media_id',
        'title',
        'app_id',
        'page_path',
        'thumb_url',
        'thumb_media_id'
    ];

    function toWechat()
    {
        return $this->belongsTo(Wechat::class, 'to_user_name', 'open_id');
    }

    function fromWechat()
    {
        return $this->belongsTo(Wechat::class, 'from_user_name', 'open_id');
    }

    function reply()
    {
        return $this->hasOne(Reply::class);
    }

}