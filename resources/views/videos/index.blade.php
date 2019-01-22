@extends('layouts.app')
@section('title', '首页')

@section('content')
    <div class="row videos">
        @forelse($rows as $row)
            <div class="col-md-4">
                <div class="fixed-image">
                    <a href="{{route('videos.show', $row->id)}}" class="cover">
                        <img src="{{$row->cover_url?:'/images/loading/video.png'}}"
                                data-original="{{$row->cover_url?:''}}">
                    </a>
                </div>
                <div class="title text-center"> {{$row->title}} </div>
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
                    <div class="updated_at"><i class="fa fa-calendar"></i> {{$row->humans_published_at}} <a
                                href="javascript:void(0)"
                                data-url="{{route("my.liked.store")}}"
                                data-video-id="{{$row->id}}"
                                class="like">@if ($row->liked)取消@endif收藏
                        </a></div>
                    <span class="liked_number"><i
                                class="fa fa-heart"></i> {{$row->formatted_liked_number?:0}}</span>
                    <span class="played_number" title="{{$row->formatted_played_number}}">
                                <i class="fa fa-play-circle"></i> {{$row->formatted_played_number?:0}}
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