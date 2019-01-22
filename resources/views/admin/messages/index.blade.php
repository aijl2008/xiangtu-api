@extends('layouts.app')
@section('title', '消息管理')
@section('content')
    <h3>消息管理</h3>
    <hr>
    @include('layouts/message')
    <table class="table table-borderless">
        <thead>
        <tr>
            <th class="text-nowrap">消息时间</th>
            <th class="text-nowrap">微信用户</th>
            <th class="text-nowrap">消息</th>
            <th class="text-nowrap">记录时间</th>
            <th class="text-nowrap">更新时间</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
                <td class="text-nowrap">{{ $row->create_time }}</td>
                <td class="text-nowrap">{{ str_limit($row->fromWechat->nickname??$row->from_user_name,24) }}</td>
                <td class="text-nowrap">
                    @if ($row->msg_type =="text")
                        <div>{{ $row->content }}</div>
                        <div>
                            <small>
                                @if ($row->reply && $row->reply->content)
                                    回复:{{$row->reply->content}}
                                @else
                                    <a data-url="{!! route('admin.messages.update', $row->id) !!}"
                                       class="reply">点此回复</a>
                                @endif
                            </small>
                        </div>
                    @elseif($row->msg_type =="image")
                        <img src="{{ $row->pic_url }}" class="img-responsive">
                    @else
                        {{$row->msg_type}}
                    @endif
                </td>
                <td class="text-nowrap">{{ $row->created_at }}</td>
                <td class="text-nowrap">{{ $row->updated_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="clearfix"></div>
    {{$rows->render()}}
    <div class="clearfix"></div>

    <div id="message-box" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div style="margin: 15px">
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <textarea name="message" id="message" class="form-control"></textarea>
                        </div>
                        <button type="button" id="reply" class="btn btn-default">回复</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script language="JavaScript">
        $(function () {
            $('#admin_message_index').addClass("active");
            var targetUrl;
            $('.reply').click(function () {
                targetUrl = $(this).data("url");
                console.log(targetUrl);
                $('#message-box').modal('show');
                return;
            });
            $('#reply').click(function () {
                if (!targetUrl) {
                    bootbox.alert({
                        title: "乡土味",
                        message: "浏览器端错误",
                        className: 'bb-alternate-modal'
                    });
                }
                var _this = $(this);
                console.log(targetUrl);
                $.ajax(
                    {
                        url: targetUrl,
                        type: "PUT",
                        data: {
                            content: $('#message').val()
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
                                        $('.modal').modal('hide');
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
                            bootbox.alert({
                                title: "乡土味",
                                message: msg,
                                className: 'bb-alternate-modal'
                            });
                        }
                    }
                );
            });
        });
    </script>
@endsection