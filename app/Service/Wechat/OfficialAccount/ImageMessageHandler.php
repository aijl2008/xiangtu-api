<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2019/1/12
 * Time: 下午11:14
 */

namespace App\Service\Wechat\OfficialAccount;

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use Illuminate\Support\Facades\Log;

class ImageMessageHandler implements EventHandlerInterface
{
    public function handle($payload = null)
    {
        Log::debug(__METHOD__);
        LogEvent::log($payload);
        return "您发送的图片{$payload['MediaId']}已接收";
    }
}