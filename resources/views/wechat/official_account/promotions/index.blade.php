@extends('layouts.app')
@section('title', '活动管理')
@section('content')
    <h3>公众号&gt;活动管理</h3>
    <div class="right"><a class="btn btn-default" href="{{route('wechat.official_account.promotions.create')}}">新建活动</a>
    </div>
    <hr>
    @include('layouts/message')
    <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>管理</th>
                <th>编号</th>
                <th class="text-nowrap">开发者微信号</th>
                <th class="text-nowrap">活动名称</th>
                <th class="text-nowrap">海报图片</th>
                <th class="text-nowrap">提示语</th>
                <th class="text-nowrap">关键词</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rows as $row)
                <tr>
                    <td class="text-nowrap">
                        <a title="删除" href='{{ route('wechat.official_account.promotions.destroy', [$row->id]) }}'
                           class='btn-sm btn-danger delete'
                           data-toggle='modal' data-target='#modal-delete'><span
                                    class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                        <a title="编辑" href='{{ route('wechat.official_account.promotions.edit', [$row->id]) }}'
                           class='btn-sm btn-warning'><span
                                    class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                    </td>
                    <td class="text-nowrap">{{ $row->id }}</td>
                    <td class="text-nowrap">{{ $row->original_id }}</td>
                    <td class="text-nowrap">{!! $row->name  !!}</td>
                    <td class="text-nowrap">{{ $row->poster }}</td>
                    <td class="text-nowrap">{!! $row->tip !!}</td>
                    <td class="text-nowrap">{!! $row->keywords !!}</td>
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
