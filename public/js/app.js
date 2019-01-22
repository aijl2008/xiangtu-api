$(function () {
    $(".like").click(function () {
        var _this = $(this);
        console.log(_this.data('video-id'));
        $.ajax(
            {
                url: _this.data('url'),
                type: "post",
                data: {
                    video_id: _this.data('video-id')
                },
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    if (res.code == 0) {
                        bootbox.alert({
                            title: "乡土味",
                            message: res.msg,
                            className: 'bb-alternate-modal'
                        });
                        _this.html(res.msg);
                    }
                    else {
                        bootbox.alert({
                            title: "乡土味",
                            message: res.msg,
                            className: 'bb-alternate-modal'
                        });
                    }
                },
                error: function (res, err, msg) {
                    console.log(res, err, msg);
                    if (res.status == 401) {
                        bootbox.alert({
                            title: "乡土味",
                            message: "收藏视频请先登录",
                            className: 'bb-alternate-modal'
                        });
                    }
                    else {
                        bootbox.alert({
                            title: "乡土味",
                            message: msg,
                            className: 'bb-alternate-modal'
                        });
                    }
                }
            }
        );
    });

    $(".follow").click(function () {
        var _this = $(this);
        var reload = _this.data('reload');
        $.ajax(
            {
                url: _this.data('url'),
                type: "post",
                data: {
                    wechat_id: _this.data('wechat-id')
                },
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    if (res.code == 0) {
                        bootbox.alert({
                            title: "乡土味",
                            message: res.msg,
                            className: 'bb-alternate-modal',
                            callback: function () {
                                if (reload) {
                                    window.location.reload();
                                }
                                _this.html(res.msg);
                            }
                        });
                    }
                    else {
                        bootbox.alert({
                            title: "乡土味",
                            message: res.msg,
                            className: 'bb-alternate-modal'
                        });
                    }
                },
                error: function (res, err, msg) {
                    console.log(res, err, msg);
                    if (res.status == 401) {
                        bootbox.alert({
                            title: "乡土味",
                            message: "关注请先登录",
                            className: 'bb-alternate-modal'
                        });
                    }
                    else {
                        bootbox.alert({
                            title: "乡土味",
                            message: msg,
                            className: 'bb-alternate-modal'
                        });
                    }
                }
            }
        );
    });
});