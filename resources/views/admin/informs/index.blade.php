@extends('layouts.app')
@section('title', '日志管理')
@section('content')
    <h3>日志管理</h3>
    <hr>
    @include('layouts/message')
    <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>编号</th>
                <th class="text-nowrap">举报人</th>
                <th class="text-nowrap">举报视频</th>
                <th class="text-nowrap">内容</th>
                <th class="text-nowrap">来自</th>
                <th class="text-nowrap">更新时间</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rows as $row)
                <tr>
                    <td class="text-nowrap">{{ $row->id }}</td>
                    <td class="text-nowrap">{{ $row->wechat->nickname }}</td>
                    <td class="text-nowrap">{!! $row->video->title  !!}</td>
                    <td class="text-nowrap">{{ $row->content }}</td>
                    <td class="text-nowrap">{!! $row->formatted_ips  !!}</td>
                    <td class="text-nowrap">{{ $row->updated_at }}</td>
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
            $('#admin_informs_index').addClass("active")
        });
    </script>
@endsection
