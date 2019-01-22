@extends('layouts.app')
@section('title', "数据统计")
@section('content')
    <h3><i class="glyphicon glyphicon-hand-right"></i> 数据统计</h3>
    <hr>
    <div class="row">
        <div class="col-md-3 col-sm-4 col-md-4">
            <div class="jumbotron" style="background: rgb(8, 183, 6);">
                <h4>视频今日播放数</h4>
                <p>{{$played_number}}</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-md-4">
            <div class="jumbotron" style="background: rgb(255, 168, 47);">
                <h4>今日新增粉丝数</h4>
                <p>{{$be_followed_number}}</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-md-4">
            <div class="jumbotron" style="background: rgb(74, 144, 226);">
                <h4>视频累计播放数</h4>
                <p>{{$total_played_number}}</p>
            </div>
        </div>
        <div class="clearfix"></div>
        <h3>近7天视频播放数</h3>
        <hr/>
        <div class="col-md-12" id="chart1" style="width: 640px; height: 480px"></div>
        <div class="clearfix"></div>
        <h3>近7天净增粉丝数</h3>
        <hr/>
        <div class="col-md-12" id="chart2" style="width: 640px; height: 480px"></div>
        <div class="clearfix"></div>
        <h3>近7天上传视频数</h3>
        <hr/>
        <div class="col-md-12" id="chart3" style="width: 640px; height: 480px"></div>
        <div class="clearfix"></div>
    </div>
@endsection
@section("js")
    <script src="/js/echarts.min.js"></script>
    <script type="text/javascript">
        $(function () {
            // var width = $('#chart1').parent().width();
            // var height = width * 2 / 4;
            // $('#chart').css("width", width).css("height", height);
            var char1 = echarts.init(document.getElementById('chart1'));

            var option1 = {
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: {!! $play_date !!}
                },
                yAxis: {
                    type: 'value'
                },
                series: [{
                    data: {!! $play_value !!},
                    type: 'line',
                    areaStyle: {}
                }]
            };
            char1.setOption(option1);

            var char2 = echarts.init(document.getElementById('chart2'));

            var option2 = {
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: {!! $follower_date !!}
                },
                yAxis: {
                    type: 'value'
                },
                series: [{
                    data: {!! $follower_value !!},
                    type: 'line',
                    areaStyle: {}
                }]
            };
            char2.setOption(option2);

            var char3 = echarts.init(document.getElementById('chart3'));

            var option3 = {
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: {!! $upload_date !!}
                },
                yAxis: {
                    type: 'value'
                },
                series: [{
                    data: {!! $upload_value !!},
                    type: 'line',
                    areaStyle: {}
                }]
            };
            char3.setOption(option3);
        });
    </script>
@endsection