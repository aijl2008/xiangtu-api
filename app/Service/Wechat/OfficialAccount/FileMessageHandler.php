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

class FileMessageHandler implements EventHandlerInterface
{
    public function handle($payload = null)
    {
        Log::debug(__METHOD__ . "({$payload['Event']})");
        LogEvent::log($payload);
        return "您发送的文件{$payload['Title']}已接收";
    }
}