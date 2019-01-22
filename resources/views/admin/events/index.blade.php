@extends('layouts.app')
@section('title', '事件管理')
@section('content')
    <h3>事件管理</h3>
    <hr>
    @include('layouts/message')
    <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>编号</th>
                <th class="text-nowrap">版本</th>
                <th class="text-nowrap">类型</th>
                <th class="text-nowrap">状态</th>
                <th class="text-nowrap">错误码</th>
                <th class="text-nowrap">错误描述</th>
                <th class="text-nowrap">更新时间</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rows as $row)
                <tr>
                    <td class="text-nowrap">{{ $row->id }}</td>
                    <td class="text-nowrap">{{ $row->version }}</td>
                    <td class="text-nowrap">{{ $row->type }}</td>
                    <td class="text-nowrap">{{ $row->status }}</td>
                    <td class="text-nowrap">{{ $row->code }}</td>
                    <td class="text-nowrap">{{ $row->message }}</td>
                    <td class="text-nowrap">{{ $row->updated_at }}</td>
                </tr>
                <tr>
                    <td colspan="7">
                        <pre>{{ $row->data }}</pre>
                    </td>
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
            $('#admin_events_index').addClass("active")
            //$("pre").css("width", $("pre").parent().width() - 20)
        });
    </script>
@endsection
