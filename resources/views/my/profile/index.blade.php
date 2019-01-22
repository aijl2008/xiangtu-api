@extends('layouts.app')
@section('title', '修改资料')
@section('content')
    <h3><i class="glyphicon glyphicon-hand-right"></i> 修改资料</h3>
    <hr>
    <div class="row">
        <div id="main-content" class="col-md-8">

            <form class="form-horizontal" id="form">
                <div class="form-group">
                    <label for="inputPassword" class="col-md-2 control-label text-right">昵称</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="nickname" id="nickname" required=""
                               value="{{$user->nickname}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-md-2 control-label text-right">手机号码</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="mobile" id="mobile" value="{{$user->mobile}}">
                    </div>
                </div>

                <div class="form-group hide">
                    <label for="inputPassword" class="col-md-2 control-label text-right">avatar</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="avatar" id="avatar" value="{{$user->avatar}}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">头像</label>
                    <div class="col-sm-10">
                        <p class="form-control-static">
                            <a href="###"><img class="img-rounded img-responsive" src="{{$user->avatar}}"></a>
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button id="submit" type="button" class="btn btn-primary">保存修改</button>
                    </div>
                </div>
            </form>
        </div>
        <form id="uploadForm" enctype="multipart/form-data" style="display: none;">
            <input type="email" name="email" value="awz@awz.cn">
            <input type="file" name="upload_avatar" id="upload_avatar">
        </form>
        <div class="clearfix"></div>
        <br />
        <br />
        @endsection
        @section('js')
            <script>
                $(function () {
                    $("#submit").on("click", function () {
                        if (!$("#avatar").val()) {
                            bootbox.alert({
                                title: "乡土味",
                                message: "请选择图片",
                                className: 'bb-alternate-modal'
                            });
                            return;
                        }
                        $.ajax({
                            url: '{{route("my.profile.update")}}',
                            type: 'POST',
                            data: $("#form").serialize(),
                            dataType: 'json',
                            success: function (res) {
                                if ("undefined" == res.code) {
                                    bootbox.alert({
                                        title: "乡土味",
                                        message: "无法解析接口状态",
                                        className: 'bb-alternate-modal'
                                    });
                                    return;
                                }
                                if (res.code == 0) {
                                    bootbox.alert({
                                        title: "乡土味",
                                        message: "修改成功",
                                        className: 'bb-alternate-modal'
                                    });
                                }
                                else {
                                    if (res.msg) {
                                        bootbox.alert({
                                            title: "乡土味",
                                            message: "修改失败," + res.msg,
                                            className: 'bb-alternate-modal'
                                        });
                                    }
                                    else {
                                        bootbox.alert({
                                            title: "乡土味",
                                            message: "修改成功",
                                            className: 'bb-alternate-modal'
                                        });
                                    }
                                }
                            },
                            error: function (res, err) {
                                bootbox.alert({
                                    title: "乡土味",
                                    message: '修改失败:' + err,
                                    className: 'bb-alternate-modal'
                                });
                            }
                        });
                    });
                    $('#upload_avatar').on('change', function () {
                        var upload_avatar = $("#upload_avatar").val();
                        if (upload_avatar) {
                            $.ajax({
                                url: '/my/profile/upload',
                                type: 'POST',
                                dataType: 'json',
                                data: (new FormData(document.getElementById('uploadForm'))),
                                async: false,
                                cache: false,
                                contentType: false, //不设置内容类型
                                processData: false, //不处理数据
                                success: function (res) {
                                    if ("undefined" == res.code) {
                                        bootbox.alert({
                                            title: "乡土味",
                                            message: "无法解析接口状态",
                                            className: 'bb-alternate-modal'
                                        });
                                        return;
                                    }
                                    if (res.code != 0) {
                                        bootbox.alert({
                                            title: "乡土味",
                                            message: "图片上传失败," + res.msg,
                                            className: 'bb-alternate-modal'
                                        });
                                        return;
                                    }
                                    $("#avatar").val("https://www.xiangtu.net.cn" + res.data.url);
                                    $('.img-rounded').attr("src", "https://www.xiangtu.net.cn" + res.data.url);
                                },
                                error: function (res, err) {
                                    bootbox.alert({
                                        title: "乡土味",
                                        message: "图片上传失败," + err,
                                        className: 'bb-alternate-modal'
                                    });
                                }
                            })
                        } else {
                            bootbox.alert({
                                title: "乡土味",
                                message: "选择的文件无效",
                                className: 'bb-alternate-modal'
                            });
                        }
                    });
                    $('.img-rounded').on('click', function () {
                        $('#upload_avatar').click();
                    });
                });
            </script>
@endsection