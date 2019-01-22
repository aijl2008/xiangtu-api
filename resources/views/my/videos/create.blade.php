@extends('layouts.app')
@section('title', '上传视频')
@section('content')
    <h3 class="red"><i class="glyphicon glyphicon-hand-right"></i> 上传视频</h3>
    <hr/>
    <form id="upload-video">
        <div class="form-group">
            <label class="col-md-2 control-label text-right">选择视频</label>
            <div class="col-md-10">
                <input class="form-control hide" type="text" value="" name="url" id="url" readonly="readonly">
                <input type="hidden" value="" name="file_id" id="file_id">
                <p class="form-control-static" id="queue_videos"><a id="addVideo"
                                                                    href="javascript:void(0);"
                                                                    class="btn btn-sm btn-default">添加视频</a></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label text-right">选择视频封面</label>
            <div class="col-md-10">
                <input class="form-control hide" type="text" value="" name="cover_url" id="cover_url"
                       readonly="readonly">
                <input type="file" id="cover" name="cover" class="hide">
                <img class="thumbnail img-responsive hide" src="">
                <p class="form-control-static" id="queue_video_covers"><a id="addCover"
                                                                          href="javascript:void(0);"
                                                                          class="btn btn-sm btn-default">添加封面</a>
                </p>
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword" class="col-md-2 control-label text-right">视频名称</label>
            <div class="col-md-10">
                <input class="form-control" type="text" name="title" id="title" required="">
            </div>
        </div>
        <div class="form-group {{ $errors->has('classification_id') ? 'has-error' : ''}}">
            {!! Form::label('classification_id', '分类', ['class' => 'col-md-2 control-label text-right']) !!}
            <div class="col-md-10">
                <div class="radio">
                    @foreach($navigation as $item)

                        <label>
                            {!! Form::radio('classification_id', $item->id); !!}
                            {{$item->name}}
                        </label>

                    @endforeach </div>
                {!! $errors->first('classification_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label text-right">谁可以看</label>
            <div class="col-md-10">
                <div class="radio">
                    <label>
                        <input type="radio" name="visibility" id="optionsRadios1" value="1">
                        任何人都可以看
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="visibility" id="optionsRadios2" value="2">
                        关注我的人可以看
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="visibility" id="optionsRadios3" value="3">
                        只有我自己可以看
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <a id="uploadFile" href="javascript:void(0);" class="btn btn-primary">上传并保存</a>
                </div>
            </div>
        </div>
    </form>
    <br/>
    <br/>
    <br/>
    <div style="display: none">
        <input type="file" id="addVideo-file">
    </div>
    <div class="clearfix"></div>
@endsection
@section('js')
    <script src="//imgcache.qq.com/open/qcloud/js/vod/sdk/ugcUploader.js"></script>
    <script type="text/javascript">

        $(function () {

            $('#my_uploader').addClass("active")
            /**
             * 用于实现取消上传的两个对象。需要在 progress 回调中赋值。
             */
            var uploadCos;
            var uploadTaskId;

            /**
             * 待上传对象，需要在选择文件时赋值。
             */
            var videoFileTask;
            var coverFileTask;

            /**
             * 计算签名
             */
            var getSignature = function (callback) {
                $.ajax({
                    url: '{{route("my.qcloud.signature.vod")}}',
                    type: 'GET',
                    dataType: 'json',
                    success: function (res) {
                        if (res.data && res.data.signature) {
                            callback(res.data.signature);
                        } else {
                            bootbox.alert({
                                title: "上传错误",
                                message: "获取签名失败",
                                className: 'bb-alternate-modal'
                            });
                            return;
                        }
                    },
                    error: function (res, err) {
                        bootbox.alert({
                            title: "上传错误",
                            message: '获取签名失败:' + err,
                            className: 'bb-alternate-modal'
                        });
                        return;
                    }
                });
            };

            var dialog = null;
            /**
             * 选择视频文件
             */
            $('#addVideo').on('click', function () {
                $('#addVideo-file').click();
            });
            $('#addVideo-file').on('change', function (e) {
                var videoFile = this.files[0];
                videoFileTask = videoFile;
                $('#queue_videos').html(videoFile.name);
                $("#title").val(videoFile.name);

            });
            /**
             * 选择封面文件
             */
            $('#addCover').on('click', function () {
                $('#cover').click();
            });
            $('#cover').on('change', function (e) {
                var formElement = document.querySelector("form");
                var formData = new FormData(formElement);
                $.ajax({
                    url: "{{route('my.videos.upload_cover')}}",
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        var files = $('input[name="cover"]').prop('files');
                        console.log(files);
                        if (files.length == 0) {
                            bootbox.alert({
                                title: "乡土味",
                                message: "请选择图片",
                                className: 'bb-alternate-modal'
                            });
                            return false;
                        }
                    },
                    success: function (res) {
                        if (res.code == 0) {
                            $(".thumbnail").attr("src", res.data.url);
                            $("#cover_url").val(res.data.url).show();
                        }
                        else {
                            bootbox.alert({
                                title: "乡土味",
                                message: res.msg,
                                className: 'bb-alternate-modal'
                            });
                        }
                    },
                    error: function () {
                        bootbox.alert({
                            title: "乡土味",
                            message: "封面上传失败",
                            className: 'bb-alternate-modal'
                        });
                    }
                });
            });

            /**
             * 启动上传
             */
            var startUploader = function () {
                dialog = bootbox.alert({
                    title: "正在上传，请不要关闭当前页",
                    message: "<div class=\"progress\">\n" +
                    "    <div class=\"progress-bar\" style=\"width: 0%;\">\n" +
                    "        <span class=\"sr-only\">0% Complete</span>\n" +
                    "    </div>\n" +
                    "</div>",
                    callback: function () {
                        var result = qcVideo.ugcUploader.cancel({
                            cos: uploadCos,
                            taskId: $(this).attr('taskId')
                        });
                        console.log(result);
                    },
                    buttons: {
                        ok: {
                            label: '取消上传',
                            callback: function () {
                                console('Ok');
                            }
                        }
                    }
                });
                var resultMsg = qcVideo.ugcUploader.start({
                    videoFile: videoFileTask,
                    coverFile: coverFileTask,
                    getSignature: getSignature,
                    allowAudio: 1,
                    success: function (result) {
                        if (result.type == 'video') {
                            console.log('视频上传成功');
                        } else if (result.type == 'cover') {
                            console.log('封面上传成功');
                        }
                    },
                    error: function (result) {
                        try {
                            bootbox.alert({
                                title: "乡土味",
                                message: result.msg,
                                className: 'bb-alternate-modal'
                            });
                        } catch (e) {
                            bootbox.alert({
                                title: "乡土味",
                                message: result.toString(),
                                className: 'bb-alternate-modal'
                            });
                        }
                    },
                    progress: function (result) {
                        if (result.type == 'video') {
                            var current_progress = Math.floor(result.curr * 100);
                            var progress_bar = $(".progress-bar");
                            progress_bar.css("width", current_progress + "%");
                            progress_bar.find(".sr-only").text(current_progress + "% 完成");
                            uploadTaskId = result.taskId;
                            uploadCos = result.cos;
                        } else if (result.type == 'cover') {
                        }
                    },
                    finish: function (result) {
                        if (result.fileId) {
                            $('#file_id').val(result.fileId);
                        }
                        if (result.coverUrl) {
                            $('#cover_url').val(result.coverUrl);
                        }
                        $('#url').val(result.videoUrl);
                        $.ajax({
                            url: '{{route("my.videos.store")}}',
                            type: 'POST',
                            data: $("#upload-video").serialize(),
                            dataType: 'json',
                            beforeSend: function () {
                                dialog.modal('hide');
                                dialog = bootbox.alert({
                                    title: "乡土味",
                                    message: "正在保存视频，请不要关闭当前页",
                                    className: 'bb-alternate-modal'
                                });
                            },
                            success: function (res) {
                                if (res && res.code == 0) {
                                    dialog.modal('hide');
                                    bootbox.alert({
                                        title: "乡土味",
                                        message: "成功的保存视频",
                                        className: 'bb-alternate-modal',
                                        callback: function () {
                                            window.location.href = "{{route('my.videos.index')}}";
                                        }
                                    });
                                }
                                else {
                                    if (res.msg) {
                                        bootbox.alert({
                                            title: "乡土味",
                                            message: res.msg,
                                            className: 'bb-alternate-modal'
                                        });
                                    }
                                    else {
                                        bootbox.alert({
                                            title: "乡土味",
                                            message: "保存视频失败",
                                            className: 'bb-alternate-modal'
                                        });
                                    }
                                }
                            },
                            error: function (res, err) {
                                $.each(res.responseJSON.errors, function (idx, message) {
                                    bootbox.alert({
                                        title: "乡土味",
                                        message: message.toString(),
                                        className: 'bb-alternate-modal'
                                    });
                                    return;
                                });
                            }
                        });
                    }
                });
            };

            /**
             * 上传按钮点击事件
             */
            $('#uploadFile').on('click', function () {
                if (!videoFileTask) {
                    bootbox.alert({
                        title: "乡土味",
                        message: "请添加视频",
                        className: 'bb-alternate-modal'
                    });
                    return;
                }
                if (!$('#title').val()) {
                    bootbox.alert({
                        title: "乡土味",
                        message: "请输入视频名称",
                        className: 'bb-alternate-modal'
                    });
                    return;
                }
                if (!$("input[name='classification_id']:checked").val()) {
                    bootbox.alert({
                        title: "乡土味",
                        message: "请选择视频分类",
                        className: 'bb-alternate-modal'
                    });
                    return;
                }
                if (!$("input[name='visibility']:checked").val()) {
                    bootbox.alert({
                        title: "乡土味",
                        message: "请选择谁可以看",
                        className: 'bb-alternate-modal'
                    });
                    return;
                }
                if (!$('#cover_url').val()) {
                    bootbox.confirm({
                        title: "乡土味",
                        message: "您没有上传视频封面，确定要继续吗？",
                        buttons: {
                            confirm: {
                                label: '继续',
                                className: 'btn-success'
                            },
                            cancel: {
                                label: '取消',
                                className: 'btn-danger'
                            }
                        },
                        callback: function (result) {
                            if (!result) {
                                return;
                            }
                            startUploader();
                        }
                    });
                }
                else {
                    startUploader();
                }

            });
        });
    </script>
@endsection
