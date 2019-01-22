@extends('layouts.app')
@section('title', '活动管理')

@section('content')
    <h3>公众号&gt;活动管理</h3>
    <hr>
    @include('layouts/message')
    {!! Form::model($row, [
                                'method' => 'PATCH',
                                'route' => ['wechat.official_account.promotions.update', $row->id],
                                'class' => 'form-horizontal',
                                'files' => true
                            ]) !!}
    {!! Form::input('hidden', 'id') !!}
    @include ('wechat.official_account.promotions.form', ['submitButtonText' => '更新'])
    {!! Form::close() !!}
@endsection