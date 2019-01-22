<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2019/1/13
 * Time: 上午12:27
 */

namespace App\Service\Wechat\OfficialAccount;


use EasyWeChat\Factory;

class OfficialAccount
{
    static $instances = [];

    static function instance($app_id)
    {
        if (!array_key_exists($app_id, self::$instances)) {
            $OfficialAccount = \App\Models\Wechat\OfficialAccount\OfficialAccount::query()->where('app_id', $app_id)->firstOrFail();
            self::$instances [$app_id] = Factory::officialAccount([
                'app_id' => $OfficialAccount->app_id,
                'secret' => $OfficialAccount->secret,
                'token' => $OfficialAccount->token,
                'aes_key' => $OfficialAccount->aes_key,
            ]);
        }
        return self::$instances [$app_id];
    }
}