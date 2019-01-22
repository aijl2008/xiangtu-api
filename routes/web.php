<?php
/**
 * 首页
 */

Route::get("/vue", "VueController@index");

/**
 * 公开页面，视频播放
 */
/**
Route::get("/videos/{video}", "VideoController@show")->name('videos.show');
Route::post("/videos/play/{video}", "VideoController@play")->name('videos.play');
Route::get("/", "VideoController@index")->name('home');
*/

Route::get("/", function(){
    return redirect()->to(route("my.videos.index"));
})->name('home');
/**
 * 管理员登录
 */
Route::group(
    [
        'prefix' => 'admin',
        "namespace" => "Admin",
        'as' => 'admin.'
    ],
    function () {
        Route::get('login', 'LoginController@showLoginForm')->name('login.show');
        Route::post('login', 'LoginController@login')->name('login.do');
        Route::get('logout', 'LoginController@logout')->name('logout');
        Route::get('register', 'RegisterController@showRegistrationForm')->name('register.show');
        Route::post('register', 'RegisterController@register')->name('register.do');
    }
);

/**
 * 公众号管理
 */
Route::group(
    [
        'middleware' => 'auth:admin',
        'prefix' => 'wechat/official_account',
        "namespace" => "Wechat\\OfficialAccount",
        'as' => 'wechat.official_account.'
    ],
    function () {
        Route::resource("/official_accounts", "OfficialAccountController");
        Route::resource("/events", "EventController");
        Route::resource("/promotions", "PromotionController");
        Route::resource("/followers", "FollowerController");
    }
);


/**
 * 管理页面组
 */
Route::group(
    [
        'middleware' => 'auth:admin',
        'prefix' => 'admin',
        "namespace" => "Admin",
        'as' => 'admin.'
    ],
    function () {
        Route::any("/uploader/upload", "UploaderController@upload")->name("uploader.upload");
        /**
         * 首页
         */
        Route::get("/", "HomeController@index")->name('home');


        Route::resource("/classifications", "ClassificationController", [
            'except' => [
                'show'
            ]
        ]);

        Route::resource("/messages", "MessageController", [
            'except' => [
                'show'
            ]
        ]);

        Route::resource("/informs", "InformsController", [
            'only' => [
                'index'
            ]
        ]);

        Route::resource("/logs", "LogController", [
            'only' => [
                'index'
            ]
        ]);

        Route::resource("/events", "EventController", [
            'only' => [
                'index'
            ]
        ]);

        Route::resource("/tasks", "TaskController", [
            'only' => [
                'index', 'show'
            ]
        ]);

        /**
         * 用户页
         */
        Route::resource("/users", "UserController", [
            'only' => [
                'index', 'show'
            ]
        ]);
        /**
         * 视频列表
         */
        Route::resource("videos", "VideoController", [
            'only' => [
                'index', 'show'
            ]
        ]);
        Route::any("videos/{video}/snapshot", "VideoController@snapshot")->name('videos.snapshot');
        Route::get("videos/{video}/status", "VideoController@status")->name('videos.status');
    }
);


/**
 * 用户登录（微信登录）
 */
Route::group(
    [
        'prefix' => 'wechat',
        'as' => 'wechat.'
    ],
    function () {
        Route::get('login', 'WechatAuthController@showLoginForm')->name('login.show');
        Route::get('login/mock/{user}', 'WechatAuthController@mock')->name('login.mock');
        Route::get('login/redirect', 'WechatAuthController@redirect')->name('login.redirect');
        Route::get('login/do', 'WechatAuthController@callback')->name('login.do');
        Route::get('logout', 'WechatAuthController@logout')->name('logout');
    }
);

/**
 * "我的"页面组
 */
Route::group(
    [
        'middleware' => 'auth:wechat',
        'prefix' => 'my',
        "namespace" => "My",
        'as' => 'my.'
    ],
    function () {
        /**f
         * 首页
         */
        Route::get("/", "HomeController@index")->name('home');
        /**
         * 视频列表
         */
        Route::resource("videos", "VideoController");
        Route::post("videos/upload_cover", "VideoController@uploadCover")->name("videos.upload_cover");
        Route::resource('followed', 'FollowController');
        Route::resource('liked', 'LikeController');

        Route::post('profile/upload', 'ProfileController@upload')->name('profile.upload');
        Route::post('profile', 'ProfileController@update')->name('profile.update');
        Route::get('profile', 'ProfileController@index')->name('profile');
        /**
         * signature
         */
        Route::get("qcloud/signature/vod", "QCloud\SignatureController@Vod")->name('qcloud.signature.vod');


        Route::Get('statistics', 'StatisticsController@index')->name('statistics.index');
    }
);

/**
 * 公众号消息接口
 */
Route::any('/wechat', 'WechatServerController@serve')->name('wechat.serve');
Route::any('/official_account/{original_id}', 'OfficialAccountController@serve')->name('wechat.official_account.serve');
Route::any('/official_account/{original_id}/qrcode', 'OfficialAccountController@qrcode')->name('wechat.official_account.qrcode');

Route::any('/qr_code/user', 'QRCodeController@user');
Route::any('/qr_code/video', 'QRCodeController@video');

Route::get('/cos/{url}', "CosController");
Route::get('/artron/{url}', "ArtronController");

Route::get('image', 'ImageController');
Route::get('graph', 'JpGraphController');

Route::Get('statistics/play/{user}', 'StatisticsController@play')->name('statistics.video');
Route::Get('statistics/follower/{user}', 'StatisticsController@follower')->name('statistics.follower');
Route::Get('statistics/upload/{user}', 'StatisticsController@upload')->name('statistics.follower');
