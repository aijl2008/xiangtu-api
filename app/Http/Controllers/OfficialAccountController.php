<?php

namespace App\Http\Controllers;

use App\Models\Wechat\OfficialAccount\Follower;
use App\Service\Wechat\OfficialAccount\EventMessageHandler;
use App\Service\Wechat\OfficialAccount\FileMessageHandler;
use App\Service\Wechat\OfficialAccount\ImageMessageHandler;
use App\Service\Wechat\OfficialAccount\LinkMessageHandler;
use App\Service\Wechat\OfficialAccount\LocationMessageHandler;
use App\Service\Wechat\OfficialAccount\MapMessageHandler;
use App\Service\Wechat\OfficialAccount\MediaMessageHandler;
use App\Service\Wechat\OfficialAccount\OfficialAccount;
use App\Service\Wechat\OfficialAccount\QRCode;
use App\Service\Wechat\OfficialAccount\TextMessageHandler;
use EasyWeChat\Kernel\Messages\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OfficialAccountController extends Controller
{
    function serve(Request $request, $app_id)
    {
        $app = OfficialAccount::instance($app_id);
        try {
            $app->server->push(EventMessageHandler::class, Message::EVENT);
            $app->server->push(ImageMessageHandler::class, Message::IMAGE);
            $app->server->push(TextMessageHandler::class, Message::TEXT);
            $app->server->push(MediaMessageHandler::class, Message::VOICE | Message::VIDEO | Message::SHORT_VIDEO);
            $app->server->push(LocationMessageHandler::class, Message::LOCATION);
            $app->server->push(MapMessageHandler::class, Message::LOCATION);
            $app->server->push(FileMessageHandler::class, Message::FILE);
            $app->server->push(LinkMessageHandler::class, Message::LINK);

            $app->server->push(function ($message) use ($app) {
                return __CLASS__ . '(' . var_export($message, true) . ')';
            });

        } catch (\Exception $e) {
            Log::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }
        return $app->server->serve();
    }

    function qrcode(Request $request, $original_id)
    {
        $Follower = Follower::query()->where('open_id', $request->input('open_id'))->firstOrFail();
        $OfficialAccount = \App\Models\Wechat\OfficialAccount\OfficialAccount::query()->where('original_id', $original_id)->firstOrFail();
        $canvas = QRCode::make($OfficialAccount->app_id, $Follower);
        return $canvas->response();
    }
}
