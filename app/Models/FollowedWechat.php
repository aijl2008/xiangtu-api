<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2018/12/12
 * Time: 下午7:01
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FollowedWechat extends Model
{
    protected $table = 'followed_wechat';
    protected $fillable = [
        'wechat_id',
        'followed_id'
    ];

//    function video()
//    {
//        return $this->hasMany(Video::class, 'wechat_id');
//    }
}