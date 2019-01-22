@extends('layouts.app')
@section('title', '修改我的视频')
@section('content')
    <h3 class="red"><i class="glyphicon glyphicon-hand-right"></i> 修改我的视频</h3>
    <hr/>
    <form id="upload-video">
        <div class="form-group">
            <label class="col-md-2 control-label text-right">选择视频封面</label>
            <div class="col-md-10">
                <input class="form-control hide" type="text" value="{{$row->cover_url}}" name="cover_url" id="cover_url"
                       readonly="readonly">
                <input class="form-control hide" type="text" value="{{$row->url}}" name="url" id="url"
                       readonly="readonly">
                <img class="thumbnail img-responsive" src="{{$row->cover_url}}">
                <input type="file" id="cover" name="cover" class="hide">
                <p class="form-control-static" id="queue_video_covers"><a id="addCover"
                                                                          href="javascript:void(0);"
                                                                          class="btn btn-sm btn-default">上传</a>
                </p>
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword" class="col-md-2 control-label text-right">视频名称</label>
            <div class="col-md-10">
                <input class="form-control" type="text" name="title" id="title" required="" value="{{$row->title}}">
            </div>
        </div>
        <div class="form-group {{ $errors->has('classification_id') ? 'has-error' : ''}}">
            {!! Form::label('classification_id', '分类', ['class' => 'col-md-2 control-label text-right']) !!}
            <div class="col-md-10">
                <div class="radio">
                    @foreach($navigation as $item)
                        <label>
                            {!! Form::radio('classification_id', $item->id, $item->id == $row->classification_id); !!}
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
                        @if($row->visibility =="1")
                            <input type="radio" name="visibility" id="optionsRadios1" value="1" checked>
                        @else
                            <input type="radio" name="visibility" id="optionsRadios1" value="1">
                        @endif
                        任何人都可以看
                    </label>
                </div>
                <div class="radio">
                    <label>
                        @if($row->visibility =="2")
                            <input type="radio" name="visibility" id="optionsRadios2" value="2" checked>
                        @else
                            <input type="radio" name="visibility" id="optionsRadios2" value="2">
                        @endif

                        关注我的人可以看
                    </label>
                </div>
                <div class="radio">
                    <label>
                        @if($row->visibility =="3")
                            <input type="radio" name="visibility" id="optionsRadios3" value="3" checked>
                        @else
                            <input type="radio" name="visibility" id="optionsRadios3" value="3">
                        @endif
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
    {!! dd($row->visibility =="1",$row->visibility =="2",$row->visibility =="3s") !!}
    <div class="clearfix"></div>
@endsection
@section('js')
    <script src="//imgcache.qq.com/open/qcloud/js/vod/sdk/ugcUploader.js"></script>
    <script type="text/javascript">

        $(function () {

            /**
             * 选择封面文件
             */

            $('#cover').on('change', function () {
                    var formElement = document.querySelector("form");
                    var formData = new FormData(formElement);
                    console.log(formData);
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
                                $("#cover_url").val(res.data.url);
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
                }
            );
            $('#addCover').on('click', function () {
                $('#cover').click();
            });
            /**
             * 上传按钮点击事件
             */
            $('#uploadFile').on('click', function () {
                $.ajax({
                    url: '{{route("my.videos.update", $row->id)}}',
                    type: 'PATCH',
                    data: $("#upload-video").serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        if (!$('#title').val()) {
                            bootbox.alert({
                                title: "乡土味",
                                message: "请输入视频名称",
                                className: 'bb-alternate-modal'
                            });
                            return false;
                        }
                        if (!$("input[name='classification_id']:checked").val()) {
                            bootbox.alert({
                                title: "乡土味",
                                message: "请选择视频分类",
                                className: 'bb-alternate-modal'
                            });
                            return false;
                        }
                        if (!$("input[name='visibility']:checked").val()) {
                            bootbox.alert({
                                title: "乡土味",
                                message: "请选择谁可以看",
                                className: 'bb-alternate-modal'
                            });
                            return false;
                        }
                        if (!$('#cover_url').val()) {
                            bootbox.alert({
                                title: "乡土味",
                                message: "请选择谁可以看",
                                className: 'bb-alternate-modal'
                            });
                            return false;
                        }
                    },
                    success: function (res) {
                        if (res && res.code == 0) {
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
            });
        });
    </script>
@endsection
