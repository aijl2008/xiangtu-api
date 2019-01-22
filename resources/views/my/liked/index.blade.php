@extends('layouts.app')
@section('title', '我收藏的视频')

@section('content')
    <h3 class="red"><i class="glyphicon glyphicon-hand-right"></i> 我收藏的视频</h3>
    <hr/>
    <div class="row">
        @forelse($rows as $row)
            <div class="col-md-4">
                <a href="{{route('videos.show', $row->id)}}" class="cover">
                    <img
                            class="thumbnail img-responsive img-rounde lazyload cover"
                            src="/images/loading/video.png"
                            data-original="{{$row->cover_url?:''}}">
                </a>
                <div class="title text-center"> {{$row->title}} </div>
                <div class="avatar-container">
                    <img src="/images/user-48.png"
                         data-original="{{$row->wechat->avatar??''}}"
                         class="img-circle lazyload avatar-middle">
                    <a
                            href="javascript:void(0);"
                            class="follow followed_number label label-default"
                            data-url="{{ route('my.followed.store') }}"
                            data-wechat-id="{{$row->wechat->id}}">关注</a>
                </div>
                <div class="video-info-container">
                    <div class="updated_at"><i class="fa fa-calendar"></i> {{$row->humans_published_at}}
                        <a
                                href="javascript:void(0);"
                                class="like"
                                data-url="{{ route('my.liked.store') }}"
                                data-video-id="{{$row->id}}">@if ($row->wechat->followed)取消@endif收藏</a></div>
                    <span class="liked_number"><i class="fa fa-heart"></i> {{$row->formatted_liked_number?:0}}</span>
                    <span class="played_number" title="{{$row->wechat_number}}">
                                <i class="fa fa-play-circle"></i> {{$row->formatted_liked_number?:0}}
                            </span>
                </div>
                <div class="clearfix"></div>
            </div>
        @empty
            <div class="no_video text-center">
                <img src="/images/no-video.png">
                <p>没有视频</p>
            </div>
        @endforelse
        <div class="col-md-12 text-center">{{$rows->links()}}</div>
    </div>
@endsection
@section('js')
    <script language="JavaScript">
        $(function () {
            $('#my_liked_index').addClass("active")
        });
    </script>
@endsection