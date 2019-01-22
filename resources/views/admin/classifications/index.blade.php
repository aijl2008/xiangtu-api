@extends('layouts.app')
@section('title', '视频分类管理')
@section('content')
    <h3>视频分类管理</h3>
    <hr>
    @include('layouts/message')
    <table class="table table-borderless">
        <thead>
        <tr>
            <th>操作</th>
            <th class="text-nowrap">分类编号</th>
            <th class="text-nowrap">分类名称</th>
            <th class="text-nowrap">分类状态</th>
            <th class="text-nowrap">创建时间</th>
            <th class="text-nowrap">更新时间</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
                <td class="text-nowrap">
                    <a title="删除" href='{{ route('admin.classifications.destroy', [$row->id]) }}'
                       class='btn-sm btn-danger delete'
                       data-toggle='modal' data-target='#modal-delete'><span
                                class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                    <a title="编辑" href='{{ route('admin.classifications.edit', [$row->id]) }}'
                       class='btn-sm btn-warning'><span
                                class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                </td>
                <td class="text-nowrap">{{ $row->id }}</td>
                <td class="text-nowrap">{{ $row->name }}</td>
                <td class="text-nowrap">{{ isset($status[$row->status])?$status[$row->status]:"未知[$row->status]" }}</td>
                <td class="text-nowrap">{{ $row->created_at }}</td>
                <td class="text-nowrap">{{ $row->updated_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
@section('js')
    <script language="JavaScript">
        $(function () {
            $('#admin_classifications_index').addClass("active")
        });
    </script>
@endsection