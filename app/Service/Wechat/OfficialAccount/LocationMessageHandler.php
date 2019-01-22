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

class LocationMessageHandler implements EventHandlerInterface
{
    public function handle($payload = null)
    {
        Log::debug(__METHOD__ . "({$payload['Event']})");
        LogEvent::log($payload);
        return "您的位置在：经度{$payload['Longitude']},纬度{$payload['Latitude']}";
    }
}