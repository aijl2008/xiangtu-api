<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2019/1/13
 * Time: 下午1:22
 */

namespace App\Service\Wechat\OfficialAccount;


use App\Models\Wechat\OfficialAccount\Follower;
use App\Models\Wechat\OfficialAccount\OfficialAccount as Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class QRCode
{
    static function make(Model $model, Follower $follower)
    {
        $app = OfficialAccount::instance($model->app_id);


        /**
         * $canvas
         */
        $promotion = $model->promotions()->orderBy('id', 'desc')->first();
        if ($promotion) {
            $canvas = Image::make(Storage::path($promotion->poster));
        } else {
            $canvas = $canvas = Image::canvas(720, 650, '#ffffff');
        }


        /**
         * cover
         */
        $canvas->text($follower->nickname, 100, 20, function ($font) {
            $font->file(base_path('/public/images/hei.ttf'));
            $font->size(36);
            $font->color('#ccc');
        });

        $canvas->text("邀请你参加活动", 100, 50, function ($font) {
            $font->file(base_path('/public/images/hei.ttf'));
            $font->size(36);
            $font->color('#ccc');
        });

        /**
         * qr_code
         */
        $result = $app->qrcode->forever($follower->open_id);
        $qrcode_url = $app->qrcode->url($result['ticket']);
        $qrcode = Image::make($qrcode_url);
        $qrcode->resize(100, 100);
        $canvas->insert($qrcode, 'bottom-right', 10, 10);

        /**
         * avatar
         */
        $avatar_url = $app->user->get($follower->open_id)['headimgurl'];
        $qrcode = Image::make($avatar_url);
        $qrcode->resize(70, 70);
        $canvas->insert($qrcode, 'top-left', 10, 10);
        return $canvas;

    }
}