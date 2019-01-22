@extends('layouts.app')
@section('title', '日志管理')
@section('content')
    <h3>公众号&gt;事件查询</h3>
    <hr>
    @include('layouts/message')
    <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>编号</th>
                <th class="text-nowrap">开发者微信号</th>
                <th class="text-nowrap">发送方</th>
                <th class="text-nowrap">消息创建时间</th>
                <th class="text-nowrap">消息类型</th>
                <th class="text-nowrap">内容</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rows as $row)
                <tr>
                    <td class="text-nowrap">{{ $row->id }}</td>
                    <td class="text-nowrap">{{ $row->ToUserName }}</td>
                    <td class="text-nowrap">{!! $row->FromUserName  !!}</td>
                    <td class="text-nowrap">{{ $row->CreateTime }}</td>
                    <td class="text-nowrap">{!! $row->MsgType  !!}</td>
                    <td class="text-nowrap">{!! $row->Content !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {!! $rows->render() !!}
@stop
@section('js')
    <script language="JavaScript">
        $(function () {
            $('#admin_logs_index').addClass("active")
        });
    </script>
@endsection
