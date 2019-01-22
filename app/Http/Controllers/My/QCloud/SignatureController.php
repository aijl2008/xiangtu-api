<?php

namespace App\Http\Controllers\My\QCloud;

use App\Http\Controllers\Controller;

class SignatureController extends Controller
{
    function vod()
    {
        $secret_id = config('wechat.cloud.api.default.secret_id');
        $secret_key = config('wechat.cloud.api.default.secret_key');

        /**
         * 确定签名的当前时间和失效时间
         * 签名有效期：1天
         */
        $current = time();
        $expired = $current + 86400;

        /**
         * 向参数列表填入参数
         */
        $arg_list = array(
            "secretId" => $secret_id,
            "currentTimeStamp" => $current,
            "expireTime" => $expired,
            'procedure' => 'setCoverBySnapshot()',
            "random" => rand());

        /**
         * 计算签名
         */
        $original = http_build_query($arg_list);
        $signature = base64_encode(hash_hmac('SHA1', $original, $secret_key, true) . $original);

        return [
            "code" => 200,
            "msg" => "Ok",
            "data" => [
                "signature" => $signature
            ]
        ];

    }
}
