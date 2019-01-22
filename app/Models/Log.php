<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2018/12/12
 * Time: 下午4:49
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Log extends Model
{

    /**
     * @var Request
     */
    protected $request = null;

    protected $fillable = [
        'action',
        'from_user_id',
        'to_user_id',
        'video_id',
        'message',
        'created_at',
        'updated_at',
        'ips',
        'user_agent'
    ];

    protected $appends = [
        'formatted_message'
    ];

    /**
     * @param Request $request
     * @return $this
     */
    function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    function footprint($id)
    {
        return $this->where('from_user_id', $id)
            ->where('action', '播放');
    }

    function from_user()
    {
        return $this->belongsTo(Wechat::class, 'from_user_id')->withDefault();
    }

    function log($action, $from_user_id = 0, $to_user_id = 0, $video_id = 0, $message = '')
    {
        $this->newQuery()->create(
            [
                'action' => $action,
                'from_user_id' => $from_user_id,
                'to_user_id' => $to_user_id,
                'video_id' => $video_id,
                'message' => $message,
                'ips' => json_encode($this->request->ips()),
                'user_agent' => $this->request->userAgent()
            ]
        );
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

    function getFormattedUserAgentAttribute()
    {
        if (!isset($this->attributes["user_agent"]) || !$this->attributes["user_agent"]) {
            return "";
        }
        $result = (new \WhichBrowser\Parser($this->attributes["user_agent"]));
        return $result->toString();
    }

    function getFormattedMessageAttribute()
    {
        if (preg_match("/\[([^\[]+)](.+)/", $this->attributes["message"], $match)) {
            $result = new \WhichBrowser\Parser($match[2]);
            $item = Log::query()->find($this->attributes['id']);
            $item->ips = $match[1];
            $item->user_agent = str_limit($match[2], 255);
            $item->message = '';
            $item->save();
            $ips = [];
            foreach ((array)json_decode($match[1], true) as $ip) {
                $ips[] = IpLocation::getInstance($ip)->__toString();
            }
            return implode('', $ips) . " " . $result->toString();
        }
        if (in_array($this->attributes['action'], ['播放', '分享到聊天'])) {
            $video = Video::query()->withoutGlobalScopes()->find($this->attributes["video_id"]);
            if (!$video) {
                return "未知视频";
            }
            return $video->wechat->nickname . '的视频，' . $video->title;
        }
        if ($this->attributes['action'] == '上传视频') {
            $video = Video::query()->withoutGlobalScopes()->find($this->attributes["video_id"]);
            if (!$video) {
                return "未知视频";
            }
            return $video->title;
        }
        if (substr($this->attributes["message"], 0, 10) == 'stdClass::') {
            return "<pre>{$this->attributes["message"]}</pre>";
        }
        return $this->attributes["message"];
    }
}