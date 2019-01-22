@extends('layouts.app')
@section('title', '我关注的人')
@section('content')
    @if (count($rows)>0)
        <h3 class="red"><i class="glyphicon glyphicon-hand-right"></i> 我关注的人</h3>
        <hr/>
        <div class="row">@foreach($rows as $row)
                <div class="col-lg-2 col-md-4 col-sm-8 col-xs-12 my-followed">
                    <img
                            class="lazyload img-responsive img-circle avatar-large"
                            src="/images/user-160.png"
                            data-original="{{$row->avatar}}">
                    <a style="display: inline-block"
                       href="javascript:void(0);"
                       class="follow followed_number btn btn-sm btn-default"
                       data-url="{{ route('my.followed.store') }}"
                       data-wechat-id="{{$row->id}}">取消关注</a>
                    <p class="nickname">{{$row->nickname}},他（她）共有位粉丝,上传了{{$row->video->count()}}个视频</p>
                </div>
            @endforeach
        </div>
        <h3 class="red"><i class="glyphicon glyphicon-hand-right"></i> 我关注的人的视频</h3>
        <hr/>
        <div class="row">
            @foreach($rows as $row)
                @foreach($row->video as $video)
                    <div class="col-lg-2 col-md-4 col-sm-8 col-xs-12">


                        <a href="{{route('videos.show', $row->id)}}" class="cover">
                            <img
                                    class="thumbnail img-responsive img-rounde lazyload cover"
                                    src="/images/loading/video.png"
                                    data-original="{{$video->cover_url?:''}}">
                        </a>
                        <div class="title text-center"> {{$video->title}} </div>
                        <div class="clearfix"></div>


                    </div>
                @endforeach
            @endforeach
        </div>
    @endif
    <h3 class="red"><i class="glyphicon glyphicon-hand-right"></i> 推荐关注</h3>
    <hr/>
    <div class="row">
        @foreach($recommended as $row)
            <div class="col-lg-2 col-md-4 col-sm-8 col-xs-12 my-followed">
                <img
                        class="lazyload img-responsive img-circle avatar-large"
                        src="/images/user-160.png"
                        data-original="{{$row->avatar}}">
                <a style="display: inline-block"
                   href="javascript:void(0);"
                   class="follow followed_number btn btn-sm btn-default"
                   data-url="{{ route('my.followed.store') }}"
                   data-wechat-id="{{$row->id}}">关注他（她）</a>
                <p class="nickname">{{$row->nickname}},他（她）共有位粉丝,上传了{{$row->video->count()}}个视频</p>
            </div>
        @endforeach
    </div>
    <div class="line"></div>


@endsection
@section('js')
    <script language="JavaScript">
        $(function () {
            $('#my_followed_index').addClass("active")
        });
    </script>
@endsection