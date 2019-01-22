@extends('layouts.app')
@section('title', '视频分类管理')

@section('content')
    <h3>视频分类管理</h3>
    <hr>
    @include('layouts/message')
    {!! Form::open(['route' => ['admin.classifications.store'], 'class' => 'form-horizontal', 'files' => true]) !!}
    @include ('admin.classifications.form')
    {!! Form::close() !!}
@endsection