<?php
return [
    'original_id' => 'gh_9d18385254ef',
    /**
     * 腾讯云
     */
    'cloud' => [
        'app_id' => '1258107170',
        'api' => [
            'default' => [
                'secret_id' => 'AKIDyrfTFpeibYP6dFMyBGDIQzTs7KmIRw8J',
                'secret_key' => "AHTelhTJ8Da2JsTy2RkWM3oPJ6pSZYTN",
            ]
        ]
    ],
    /*
     * 公众号
     */
    'official_account' => [
        'default' => [
            'app_id' => "wxda5f7e86d91086d5",
            'secret' => "1b09e65b71c01f7631efb3a71b7c145e",
            'token' => "wechatapiawzcn",
            'aes_key' => "wechatapiawzcn",
        ],
    ],

    /*
     * 开放平台第三方平台
     */
    'open_platform' => [
        'default' => [
            'app_id' => "wxb783a4e5aad0e5bc",
            'secret' => "8d14a3b9d67ac84abaf135a012086dec",
            'token' => "",
            'aes_key' => "",
            'oauth' => [
                'scopes' => ['snsapi_login'],
                'callback' => "https://passport.artron.net/wechat/code?type=open&config=wechat&redirect=",
            ],
        ],
    ],

    /*
     * 小程序
     */
    'mini_program' => [
        'default' => [
            'app_id' => 'wxbe20d80072eaf2d3',
            'secret' => '0cfb1809377ba6cd225c21968381e98c',
            'token' => 'xiangtu',
            'aes_key' => 'sJWkoqGW7KmXGt1FoIOhh0oy9BPawf8OZU1C8R2Ik65',
            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => storage_path() . '/wechat.log',
            ]
        ],
    ],

];
