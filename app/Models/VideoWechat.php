<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2018/12/12
 * Time: 下午9:07
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class VideoWechat extends Model
{
    protected $table = 'video_wechat';

    function followed()
    {
        return $this->belongsTo(FollowedWechat::class, 'wechat_id', 'wechat_id');
    }
}