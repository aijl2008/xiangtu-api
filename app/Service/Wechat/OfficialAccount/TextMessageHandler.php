<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2019/1/12
 * Time: 下午11:13
 */

namespace App\Service\Wechat\OfficialAccount;


use App\Jobs\Wechat\OfficialAccount\CreateQRCodeJob;
use App\Jobs\Wechat\OfficialAccount\UpdateUserInfoJob;
use App\Models\Wechat\OfficialAccount\Follower;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use Illuminate\Support\Facades\Log;

class TextMessageHandler implements EventHandlerInterface
{
    public function handle($payload = null)
    {
        Log::debug(__METHOD__);
        LogEvent::log($payload);
        $payload["Content"] = trim($payload["Content"]);
        switch ($payload["Content"]) {
            case '更新我的资料':
                $Follower = Follower::query()->where('open_id', $payload['FromUserName'])->first();
                if (!$Follower) {
                    $Follower = new Follower();
                    $Follower->open_id = $payload['FromUserName'];
                    $Follower->save();
                }
                dispatch(new UpdateUserInfoJob(request()->route("original_id"), $Follower));
                return '您发起了更新个人资料的指令，该指令已成功能投递到任务池中';
                break;

            case '查看我的资料':
                $Follower = Follower::query()->where('open_id', $payload['FromUserName'])->first();
                if (!$Follower) {
                    return "没有找到您的资料";
                }
                return print_r($Follower->toArray(), true);
                break;


            case '领奖':
                dispatch(new CreateQRCodeJob(
                        \App\Models\Wechat\OfficialAccount\OfficialAccount::query()->where('original_id', $payload["ToUserName"])->first(),
                        Follower::query()->where('open_id', $payload['FromUserName'])->first()
                    )
                );
                return "个性化海报正在制作中，一会儿您将收到一张图片";
                break;

            default:
                return '您输入的是:' . $payload["Content"];
                break;
        }
    }
}