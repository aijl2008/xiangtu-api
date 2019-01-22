@extends('layouts.app')
@section('title', '活动管理')

@section('content')
    <h3>公众号&gt;活动管理</h3>
    <hr>
    @include('layouts/message')
    {!! Form::open(['route' => ['wechat.official_account.promotions.store'], 'class' => 'form-horizontal', 'files' => true]) !!}
    @include ('wechat.official_account.promotions.form')
    {!! Form::close() !!}
@endsection