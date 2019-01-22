@extends('layouts.app')
@section('title', '微信登录')

@section('content')
    <div class="row login">
        <div class="col-md-4">
            <div id="login_container" class="center-block text-center"></div>
        </div>
        <div class="col-md-8">
            <div class="row recommend">
                @foreach($rows as $row)
                    <div class="col-md-3 col-sm-3 col-xm-3">
                        <a href="{{route('wechat.login.mock', $row->id)}}">
                            <img src="/images/user-48.png" data-original="{{$row->avatar}}"
                                 class="lazyload img-responsive img-circle avatar-large">
                        </a>
                        <p class="nickname">{{$row->nickname}}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="//res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
    <script type="text/javascript">
        $(function () {
            var obj = new WxLogin({
                self_redirect: false,
                id: "login_container",
                appid: "{{config('wechat.open_platform.default.app_id')}}",
                scope: "snsapi_login",
                redirect_uri: "{{route('wechat.login.do')}}",
                state: "{{ csrf_token() }}",
                style: "",
                href: ""
            });
        });
    </script>
@endsection

