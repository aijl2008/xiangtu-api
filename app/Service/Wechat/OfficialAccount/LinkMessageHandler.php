<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2019/1/12
 * Time: 下午11:12
 */

namespace App\Service\Wechat\OfficialAccount;

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use Illuminate\Support\Facades\Log;

class LinkMessageHandler implements EventHandlerInterface
{
    public function handle($payload = null)
    {
        Log::debug(__METHOD__ . "({$payload['Event']})");
        LogEvent::log($payload);
        return "您发送的网址{$payload['Title']}已收到";
    }
}