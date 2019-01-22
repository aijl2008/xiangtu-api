@extends('layouts.app')
@section('title', '视频详情')
@section('content')
    <h3>视频管理</h3>
    <div class="row">
        <div class="col-md-6">
            <img src="{{$row->cover_url}}" style="width: 320px" class="img-responsive">
            <br />
            <button type="button" class="snapshot" data-url="{{route('admin.videos.snapshot',$row->id)}}">截取封面图</button>
        </div>
        <div class="col-md-6">
            <h3 class="vid-name"><a href="#">{{$row->title}}</a></h3>
            <hr>
            <dl class="dl-horizontal">
                <dt>分类</dt>
                <dd class="text-nowrap">{{$row->classification->name}}</dd>
                <dt>播放与收藏</dt>
                <dd class="text-nowrap">{{$row->formatted_played_number}}/{{$row->formatted_liked_number}}</dd>
                <dt>可见范围</dt>
                <dd class="text-nowrap">{{$row->visibility}}</dd>
                <dt>发布时间</dt>
                <dd class="text-nowrap">{{$row->created_at}}</dd>
                <dt>发布人</dt>
                <dd class="text-nowrap">{{$row->wechat->nickname}}</dd>
                <dt>时长</dt>
                <dd class="text-nowrap">{{$row->duration}}</dd>
                <dt>文件大小文件大小</dt>
                <dd class="text-nowrap">{{$row->size}}</dd>
                <dt>文件编号</dt>
                <dd class="text-nowrap">{{$row->file_id}}</dd>
                <dt>视频地址</dt>
                <dd class="text-nowrap"><a href="{{$row->url}}" target="_blank">在新窗口打开</a></dd>
            </dl>
        </div>
        <div class="col-md-12 table-responsive">
            @if ($row->task->count() > 0)
                <table class="table table-bordered">
                    <tr>
                        <th>序号</th>
                        <th>任务编号</th>
                        <th>错误编号</th>
                        <th>错误描述</th>
                        <th>附加消息</th>
                        <th>创建时间</th>
                        <th>更新时间</th>
                    </tr>
                    @foreach ($row->task as $task)
                        <tr>
                            <td>{{$task->id}}</td>
                            <td><a href="{{route('admin.tasks.show', $task->id)}}">{{$task->task_id}}</a></td>
                            <td>{{$task->code}}</td>
                            <td>{{$task->code_desc}}</td>
                            <td>{{$task->message}}</td>
                            <td>{{$task->created_at}}</td>
                            <td>{{$task->updated_at}}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </div>
@endsection
@section('js')
    <script language="JavaScript">
        $(function () {
            $('#admin_videos_index').addClass("active");
            $(".snapshot").click(function () {
                $.ajax(
                    {
                        url: $(this).data('url'),
                        type: "get",
                        dataType: "json",
                        success: function (res) {
                            if (res.code == 0) {
                                alert(res.msg);
                                // bootbox.alert({
                                //     title: "乡土味",
                                //     message: res.msg,
                                //     className: 'bb-alternate-modal',
                                //     callback: function () {
                                //         window.location.reload();
                                //     }
                                // });
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
    </script>
@endsection