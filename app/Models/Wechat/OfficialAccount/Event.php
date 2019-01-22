<?php

namespace App\Models\Wechat\OfficialAccount;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = "official_account_events";
    protected $fillable = [
        "ToUserName",
        "FromUserName",
        "CreateTime",
        "MsgType",
        "Event",
        "EventKey",
        "Ticket",
        "Content",
        "MsgId",
        "MediaId",
        "PicUrl",
        "Format",
        "Recognition",
        "ThumbMediaId",
        "Latitude",
        "Longitude",
        "Precision",
        "Location_X",
        "Location_Y",
        "Scale",
        "Label",
        "Title",
        "Description",
        "Url",
        "FileKey",
        "FileMd5",
        "FileTotalLen",
    ];

    function getContentAttribute()
    {
        switch ($this->attributes['MsgType']) {
            case 'event':
                switch ($this->attributes['Event']) {
                    case 'subscribe':
                        if ($this->attributes["EventKey"] && stripos($this->attributes["EventKey"], "qrscene_") === 0) {
                            $open_id = str_replace("qrscene_", "", $this->attributes["EventKey"]);
                            $follower = Follower::query()->where('open_id', $open_id);
                            return "用户再次扫描了" . $follower->nickname ?? $open_id . "的二维码";
                        }
                        return "用户关注公众号";
                        break;
                    case 'unsubscribe':
                        return "用户取消关注公众号";
                        break;
                    case 'SCAN':
                        $follower = Follower::query()->where('open_id', $this->attributes["EventKey"]);
                        return "用户再次扫描了" . ($follower->nickname ?? $this->attributes["EventKey"]) . "的二维码";
                        break;
                    case 'LOCATION':
                        return "用户上报了地理位置,经度:{$this->attributes['Longitude']},纬度:{$this->attributes['Latitude']},精度:{$this->attributes['Precision']}";
                        break;
                    default:
                        return "收到事件" . $this->attributes["Event"];
                        break;
                }
                break;
            case 'text':
                return "收到文本消息," . $this->attributes["Content"];
                break;
            case 'image':
                return "收到图片消息,<img style=\"max-width:100px\" src='" . $this->attributes["PicUrl"] . "' />";
                break;
            case 'voice':
                return "收到语音消息,媒体id:{$this->attributes['MediaId']},语音格式:{$this->attributes['Format']}";
                break;
            case 'video':
                return "收到视频消息,媒体id:{$this->attributes['MediaId']},缩略图的媒体id:{$this->attributes['ThumbMediaId']}";
                break;
            case 'shortvideo':
                return "收到视频消息,媒体id:{$this->attributes['MediaId']},缩略图的媒体id:{$this->attributes['ThumbMediaId']}";
                break;
            case 'location':
                return "收到地理位置,经度:{$this->attributes['Location_Y']},纬度:{$this->attributes['Location_X']},地图缩放大小:{$this->attributes['Scale']},地理位置信息:{$this->attributes['Label']}";
                break;
            case 'link':
                return "收到文本消息,<a href='" . $this->attributes["Url"] . "'>" . $this->attributes["Title"] . "</a>" . $this->attributes["Description"];
                break;
        }
    }
}

