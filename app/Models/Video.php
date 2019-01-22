<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{

    const VISIBILITY_ANY = 1;
    const VISIBILITY_ONLY_FOLLOWED = 2;
    const VISIBILITY_ONLY_ME = 3;
    const STATUS_DISABLE = -100;
    const STATUS_TRANSFERING = 0;
    const STATUS_OK = 1;
    public $timestamps = true;

    protected $fillable = [
        "wechat_id",
        "uploaded_at",
        "title",
        "url",
        "cover_url",
        "file_id",
        "played_number",
        "liked_number",
        "shared_wechat_number",
        "shared_moment_number",
        "visibility",
        "classification_id",
        "status"
    ];

    protected $appends = [
        'published_at',
        'humans_published_at',
        "formatted_played_number",
        "formatted_liked_number",
        "formatted_shared_wechat_number",
        "formatted_shared_moment_number",
        "formatted_duration",
    ];


    public function getOption()
    {
        return [
            self::STATUS_TRANSFERING => '转码中',
            self::STATUS_DISABLE => '屏蔽',
            self::STATUS_OK => '正常',
        ];
    }

    function getVisibilitiesOptions()
    {
        return [
            '1' => '仅自己可见',
            '2' => '关注我的人可见',
            '3' => '任何人可见'
        ];
    }

    function getStatusOption()
    {
        return [
            1 => '正常',
            0 => '转码中',
        ];
    }

    function task()
    {
        return $this->hasMany(Task::class, 'file_id', 'file_id');
    }

    function wechat()
    {
        return $this->belongsTo(Wechat::class);
    }

    function classification()
    {
        return $this->belongsTo(Classification::class)->withDefault();
    }

    function liker()
    {
        return $this->belongsToMany(Wechat::class);
    }

    function followed()
    {
        //return $this->hasMany(VideoWechat::class, 'video_id', 'id');
        return $this->hasManyThrough(FollowedWechat::class, VideoWechat::class, 'wechat_id', 'wechat_id', 'wechat_id');
    }

    function getHumansPublishedAtAttribute()
    {
        return $this->attributes['humans_published_at'] = Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }

    function getFormattedLikedNumberAttribute()
    {
        if (!array_key_exists('liked_numberliked_number', $this->attributes)) {
            return '  0';
        }
        if ($this->attributes['liked_number'] < 10) {
            return '  ' . $this->attributes['liked_number'];
        }
        if ($this->attributes['liked_number'] < 100) {
            return ' ' . $this->attributes['liked_number'];
        }
        if ($this->attributes['liked_number'] > 999) {
            return '999+';
        }
    }

    function getFormattedPlayedNumberAttribute()
    {
        if (!array_key_exists('played_number', $this->attributes)) {
            return '  0';
        }
        if ($this->attributes['played_number'] < 10) {
            return '  ' . $this->attributes['played_number'];
        }
        if ($this->attributes['played_number'] < 100) {
            return ' ' . $this->attributes['played_number'];
        }
        if ($this->attributes['played_number'] > 999) {
            return '999+';
        }
    }

    function getFormattedSharedWechatNumberAttribute()
    {
        if (!array_key_exists('shared_wechat_number', $this->attributes)) {
            return '  0';
        }
        if ($this->attributes['shared_wechat_number'] < 10) {
            return '  ' . $this->attributes['shared_wechat_number'];
        }
        if ($this->attributes['shared_wechat_number'] < 100) {
            return ' ' . $this->attributes['shared_wechat_number'];
        }
        if ($this->attributes['shared_wechat_number'] > 999) {
            return '999+';
        }
    }

    function getFormattedSharedMomentNumberAttribute()
    {
        if (!array_key_exists('shared_moment_number', $this->attributes)) {
            return '  0';
        }
        if ($this->attributes['shared_moment_number'] < 10) {
            return '  ' . $this->attributes['shared_moment_number'];
        }
        if ($this->attributes['shared_moment_number'] < 100) {
            return ' ' . $this->attributes['shared_moment_number'];
        }
        if ($this->attributes['shared_moment_number'] > 999) {
            return '999+';
        }
    }

    function getFormattedDurationAttribute()
    {
        if (isset($this->attributes['duration']) && $this->attributes['duration']) {
            return $this->attributes['duration'];
        }
        return "";

    }


    function getPublishedAtAttribute()
    {
        $diff = Carbon::parse($this->attributes['updated_at'])->diffInDays(Carbon::now());
        if ($diff == 0) {
            $this->attributes['published_at'] = '今天';
        } elseif ($diff == 1) {
            $this->attributes['published_at'] = '昨天';
        } elseif ($diff < 8) {
            $this->attributes['published_at'] = '7天以内';
        } else {
            $this->attributes['published_at'] = '7天以上';
        }
        return $this->attributes['published_at'];
    }


    function getCoverUrlAttribute()
    {
        if (stripos($this->attributes['cover_url'], 'artimg.net')) {
            return "https://www.xiangtu.net.cn/artron/" . base64_encode($this->attributes['cover_url']);
        }
        return str_replace('http://', 'https://', $this->attributes['cover_url']);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status', '=', self::STATUS_OK);
        });

    }
}
