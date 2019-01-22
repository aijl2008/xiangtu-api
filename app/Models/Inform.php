<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inform extends Model
{
    protected $fillable = [
        "wechat_id", "video_id", "ips", "content"
    ];

    function wechat()
    {
        return $this->belongsTo(Wechat::class, 'wechat_id')->withDefault();
    }

    function video()
    {
        return $this->belongsTo(Video::class, 'video_id')->withDefault();
    }

    function getFormattedIpsAttribute()
    {
        if (!isset($this->attributes["ips"]) || !$this->attributes["ips"]) {
            return "";
        }
        $ips = [];
        foreach ((array)json_decode($this->attributes["ips"], true) as $ip) {
            $ips[] = IpLocation::getInstance($ip)->__toString();
        }
        return implode('', $ips);
    }
}
