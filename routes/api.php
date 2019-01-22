<?php

/**
 * 小程序快捷登录
 */
Route::any('mini_program/token', 'Api\MiniProgramController@token')->name('api.mini_program.token');

/**
 * 全部视频
 */
Route::resource('videos', 'Api\VideoController', [
    'only' => [
        'index', 'show'
    ]
]);
Route::post("/videos/play/{video}", "VideoController@play")->name('api.videos.play');
Route::post("/videos/share_to_wechat/{video}", "VideoController@shareToWechat")->name('api.videos.share_to_wechat');
Route::post("/videos/share_to_moment/{video}", "VideoController@shareToMoment")->name('api.videos.share_to_moment');
/**
 * 推荐的用户
 */
Route::get('wechat/recommend', 'Api\WechatController@recommend');
Route::get('wechat/{wechat}', 'Api\WechatController@show');

/**
 * 全部用户
 */
Route::resource('users', 'Api\WechatController',[
    "only" => [
        "index", "show"
    ]
]);
/**
 * 举报
 */
Route::post("inform", "InformController");

/**
 * Vod 事件服务
 */
Route::any('vod/service/event', 'Api\Vod\ServiceController@event');
Route::any('vod/service', 'Api\Vod\ServiceController@event');
/**
 * 视频分类
 */
Route::resource('classifications', 'Api\ClassificationController', [
    'only' => ['index']
]);

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix' => '',
        'namespace' => 'Api',
        'as' => 'api.'
    ],
    function () {
        /**
         * 显示小程序当前用户，方便token测试
         */
        Route::any('mini_program/user', 'MiniProgramController@user')->name('mini_program.user');
        /**
         * VOD上传签名
         */
        Route::get("qcloud/signature/vod", "QCloud\SignatureController@Vod")->name('qcloud.signature.vod');

        /**
         * 分享签名
         */
        Route::get("wechat/signature/share", "Wechat\SignatureController@share")->name('wechat.signature.share');

        Route::group(
            [
                'prefix' => 'my',
                'namespace' => 'My',
                'as' => 'my.'
            ],
            function () {
                /**
                 * 我的视频
                 */
                Route::resource("videos", "VideoController");
                /**
                 * 我看过的视频
                 */
                Route::resource("history", "HistoryController", [
                    "only" => "index"
                ]);
                /**
                 *  我关注的
                 */
                Route::resource('followed', 'FollowController');
                /**
                 * 我喜欢的
                 */
                Route::resource('liked', 'LikeController');

                /**
                 * 个人资料显示与修改
                 */
                Route::get('profile', 'ProfileController@index')->name('profile.show');
                Route::post('profile', 'ProfileController@update')->name('profile.update');


                Route::Get('statistics/video', 'StatisticsController@video')->name('statistics.video');
                Route::Get('statistics/follower', 'StatisticsController@follower')->name('statistics.follower');
                Route::Get('statistics/upload', 'StatisticsController@upload')->name('statistics.follower');
            }
        );

        /**
         * 统计
         */
        Route::Get('my/statistics', 'My\StatisticsController')->name('users.statistics.show');

    }
);
