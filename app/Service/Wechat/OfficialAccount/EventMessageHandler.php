<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2019/1/12
 * Time: 下午11:12
 */

namespace App\Service\Wechat\OfficialAccount;

use App\Jobs\Wechat\OfficialAccount\UpdateUserInfoJob;
use App\Models\Wechat\OfficialAccount\Follower;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use Illuminate\Support\Facades\Log;

class EventMessageHandler implements EventHandlerInterface
{

    protected function showWelcome($original_id)
    {
        $promotion = \App\Models\Wechat\OfficialAccount\OfficialAccount::query()->where('original_id', $original_id)->first()->promotions()->first();
        return $promotion->tip;
    }

    public function handle($payload = null)
    {
        Log::debug(__METHOD__ . "({$payload['Event']})");
        LogEvent::log($payload);
        switch ($payload['Event']) {
            case 'SCAN':
                $Follower = Follower::query()->where('open_id', $payload['FromUserName'])->first();
                if ($Follower) {
                    if ($Follower->canceled_at) {
                        $Follower->canceled_at = null;
                        $Follower->save();
                    } else {

                    }
                } else {
                    $Follower = new Follower();
                    $Follower->from_open_id = str_replace('qrscene_', '', $payload['EventKey']);
                    $Follower->open_id = $payload['FromUserName'];
                    $Follower->original_id = $payload['ToUserName'];
                    $Follower->subscribe_time = $payload['CreateTime'];
                    $Follower->followed_at = date('Y-m-d H:i:s', $payload['CreateTime']);
                    $Follower->save();
                    $this->follower->original_id = request()->route('original_id');

                    dispatch(new UpdateUserInfoJob(request()->route("original_id"), $Follower));
                }
                return $this->showWelcome($payload["ToUserName"]);
                break;
            case "subscribe":
                $Follower = Follower::query()->where('open_id', $payload['FromUserName'])->first();
                if (!$Follower) {
                    if (!$Follower) {
                        $Follower = new Follower();
                        $Follower->open_id = $payload['FromUserName'];
                        $Follower->original_id = $payload['ToUserName'];
                        $Follower->subscribe_time = $payload['CreateTime'];
                        $Follower->save();
                    }
                }
                dispatch(new UpdateUserInfoJob(request()->route("original_id"), $Follower));
                return $this->showWelcome($payload["ToUserName"]);
                break;
            case "unsubscribe":
                $Follower = Follower::query()->where('open_id', $payload['FromUserName'])->first();
                if ($Follower) {
                    $Follower->canceled_at = $payload["CreateTime"];
                    $Follower->save();
                }
                break;
            default:
                return $payload['Event'];
        }
    }
}