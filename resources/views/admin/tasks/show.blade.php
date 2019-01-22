@extends('layouts.app')
@section('title', '任务查看')
@section('content')
    <h3>任务查看</h3>
    <hr>
    @include('layouts/message')

    <form class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2 control-label">状态编码</label>
            <div class="col-sm-10">
                <p class="form-control-static">{{$row->code}}</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">附加消息</label>
            <div class="col-sm-10">
                <p class="form-control-static">{{$row->message}}</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">任务创建时间</label>
            <div class="col-sm-10">
                <p class="form-control-static">{{date('Y-m-d H:i:s', $row->createTime)}}</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">任务类型</label>
            <div class="col-sm-10">
                <p class="form-control-static">{{$row->type}}</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">开始处理时间</label>
            <div class="col-sm-10">
                <p class="form-control-static">{{date('Y-m-d H:i:s', $row->beginProcessTime)}}</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">完成时间</label>
            <div class="col-sm-10">
                <p class="form-control-static">{{date('Y-m-d H:i:s', $row->finishTime)}}</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">任务状态</label>
            <div class="col-sm-10">
                <p class="form-control-static">{{$row->status}}</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">结果数据</label>
            <div class="col-sm-10">
                <p class="form-control-static">
                <pre>{{print_r($row->data)}}</pre>
                </pre>
            </div>
        </div>

    </form>
@stop
@section('js')
    <script language="JavaScript">
        $(function () {
            $('#admin_tasks_index').addClass("active")
        });
    </script>
@endsection
