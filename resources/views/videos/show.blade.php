@extends('layouts.app')
@section('title', str_limit($row->title))
@section('content')
    <div class="row">
        <div class="col-md-8 video player_container">
            <h3 class="vid-name">{{$row->title}}</h3>
            <hr/>
            <video poster="{{$row->cover_url}}" id="player-container-id" preload="auto" playsinline
                   webkit-playsinline>
            </video>

            <div class="avatar-container">
                <img src="/images/user-48.png"
                     data-original="{{$row->wechat->avatar??''}}"
                     class="img-circle lazyload avatar-middle">
                <a
                        href="javascript:void(0);"
                        class="follow followed_number label label-default"
                        data-url="{{ route('my.followed.store') }}"
                        data-wechat-id="{{$row->wechat->id}}">@if ($row->wechat->followed)取消@endif关注</a>
            </div>
            <div class="video-info-container">
                <div class="updated_at"><i class="fa fa-calendar"></i> {{$row->humans_published_at}}
                    <a
                            href="javascript:void(0);"
                            class="like"
                            data-url="{{ route('my.liked.store') }}"
                            data-video-id="{{$row->id}}">@if ($row->wechat->followed)取消@endif收藏</a>
                </div>
                <span class="liked_number"><i class="fa fa-heart"></i> {{$row->formatted_liked_number?:0}}</span>
                <span class="played_number" title="{{$row->played_number}}">
                                <i class="fa fa-play-circle"></i> {{$row->formatted_played_number?:0}}
                            </span>
            </div>
            <div class="clearfix"></div>

            <br>
            <div class="tip"><img src="/images/wifi-signal-24.png"> <strong>相关视频</strong></div>
            <hr>
            <div class="row">
                @foreach($related as $item)
                    <div class="col-md-3 related">
                        <a href="{{route('videos.show', $item->id)}}">
                            <img
                                    class="thumbnail img-responsive img-rounde lazyload"
                                    src="/images/loading/video.png"
                                    data-original="{{$item->cover_url?:''}}">
                        </a>
                        <p> {{$item->title}} </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section("js")
    <link href="//imgcache.qq.com/open/qcloud/video/tcplayer/tcplayer.css" rel="stylesheet">
    <script src="//imgcache.qq.com/open/qcloud/video/tcplayer/tcplayer.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var player_container_width = $('.player_container').width();
            var option = {
                "cover": "{{$row->cover_url}}",
                "fileID": "{{$row->file_id}}",
                "appID": "{{config('wechat.cloud.app_id')}}",
                "width": player_container_width,
                "height": player_container_width * 0.75,
                "third_video":
                    {
                        "urls":
                            {
                                20:
                                    "{{$row->url}}"
                            }
                    }
            };
            var player = new TCPlayer(
                "player-container-id",
                option
            );
            player.on("play", function () {
                $.ajax(
                    {
                        url: "{{route('videos.play', $row->id)}}",
                        type: "post",
                        dataType: "json",
                        success: function (res) {
                            console.log(res);
                        },
                        error: function (res, err, msg) {
                            console.log(res, err, msg);
                        }
                    }
                );
            });
        });
    </script>
@endsection