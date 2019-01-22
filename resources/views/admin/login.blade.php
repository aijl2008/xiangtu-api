@extends('layouts.app')
@section('title', '管理员登录')
@section('content')
    <style>
        .login{
            margin-top: 30px;
        }
    </style>
    <div class="container">
        <div class="col-md-8 login">
            <form class="form-horizontal" method="POST" action="{{ route('admin.login.do') }}">
                @csrf
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">邮箱或用户名</label>
                    <div class="col-sm-10">
                        <input id="email" type="email"
                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               name="email" value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert"></span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">密码</label>
                    <div class="col-sm-10">
                        <input id="password" type="password"
                               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                               name="password" required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Remember me
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">登录</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
