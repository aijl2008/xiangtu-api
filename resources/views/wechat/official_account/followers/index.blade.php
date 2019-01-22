@extends('layouts.app')
@section('title', '全部用户')
@section('content')
    <h3>用户管理</h3>
    <hr>
    <form action="" method="get" class="form-inline">
        {!! Form::input('text', 'nickname', null, ['class' => 'form-control', 'placeholder' => '昵称']) !!}
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    <br/>
    <div class="rows">
        @foreach($rows as $row)
            <div class="col-md-6">
                <img src="{{$row->headimgurl}}" class="thumbnail">
                <dl class="dl-horizontal">
                    <dt>公众号</dt>
                    <dd>{{$row->original_id}}</dd>
                    <dt>推荐人</dt>
                    <dd>{{$row->from_open_id}}</dd>
                    <dt>用户微信号</dt>
                    <dd>{{$row->open_id}}</dd>

                    <dt>国家</dt>
                    <dd>{{$row->country}}</dd>
                    <dt>省份城市</dt>
                    <dd>{{$row->province}}{{$row->city}}</dd>

                    <dt>用户昵称</dt>
                    <dd>{{$row->nickname}}</dd>

                    <dt>性别</dt>
                    <dd>{{$row->from_open_id}}</dd>
                    <dt>语言</dt>
                    <dd>{{$row->from_open_id}}</dd>
                    <dt>组</dt>
                    <dd>{{$row->groupid}}</dd>
                    <dt>标签</dt>
                    <dd>{{$row->tagid_list}}</dd>
                    <dt>渠道来源</dt>
                    <dd>{{$row->subscribe_scene}}</dd>
                    <dt>码场景</dt>
                    <dd>{{$row->qr_scene}}</dd>
                    <dt>场景描述</dt>
                    <dd>{{$row->qr_scene_str}}</dd>
                    <dt>关注时间</dt>
                    <dd>{{$row->followed_at}}</dd>
                    <dt>取关时间</dt>
                    <dd>{{$row->canceled_at}}</dd>

                </dl>
            </div>
        @endforeach
    </div>
    <div class="clearfix"></div>
    <div class="text-center">{{$rows->links()}}</div>
    <div class="clearfix"></div>
@endsection
@section('js')
    <script language="JavaScript">
        $(function () {
            $('#admin_users_index').addClass("active")
        });
    </script>
@endsection