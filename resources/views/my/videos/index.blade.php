@extends('layouts.app')
@section('title', '我的视频')

@section('content')
    <h3 class="red"><i class="glyphicon glyphicon-hand-right"></i> 我上传的视频</h3>
    <hr/>
    <div class="row my-videos">
        @forelse($rows as $row)
            <div class="col-md-4">
                <div class="fixed-image">
                    <a href="{{route('videos.show', $row->id)}}" class="cover">
                        <img src="{{$row->cover_url?:'/images/loading/video.png'}}">
                    </a>
                </div>
                <div class="my-videos">
                    <div class="updated_at"><i class="fa fa-calendar"></i> {{$row->humans_published_at}} </div>
                    <span class="liked_number"><i class="fa fa-heart"></i> {{$row->formatted_liked_number?:0}}</span>
                    <span class="played_number" title="{{$row->wechat_number}}"><i
                                class="fa fa-play-circle"></i> {{$row->formatted_liked_number?:0}}</span>
                    <span class="label label-default">{{$status[$row->status]??'未知状态'}}</span>
                    <div class="dropdown">
                        <span class="dropdown-toggle" type="button" id="dropdownMenu{{$row->id}}" data-toggle="dropdown"
                              aria-haspopup="true" aria-expanded="true">
                            <i class="glyphicon glyphicon-cog"></i> 管理
                            <span class="caret"></span>
                        </span>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu{{$row->id}}">
                            <li><a href="javascript:void(0);" class="get_mini_program_page"
                                   data-url="pages/detail/detail?id={{$row->id}}">获取公众号嵌入路径</a></li>
                            <li><a href="javascript:void(0);" class="get_video_covers"
                                   data-id="{{$row->id}}">获取公众号嵌入图片</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{route('my.videos.edit', $row->id)}}">编辑封面/标题/权限</a></li>
                            <li><a href="javascript:void(0);" class="show_share_guide" data-id="{{$row->id}}">分享视频</a>
                            </li>
                            <li><a href="{{route('my.videos.destroy', $row->id)}}" class="destroy_video">删除视频</a></li>
                        </ul>
                    </div>
                </div>
                <div class="title text-center"> {{$row->title}} </div>
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

    <div class="modal fade" id="show_share_guide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">请使用微信扫描下方的二维码</h4>
                </div>
                <div class="modal-body">
                    <img class="thumbnail img-responsive" src="">
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="get_mini_program_page" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">请选中下方的网址后，使用ctrl+c复制</h4>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" rows="5"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="clearfix"></div>
@endsection

@section('js')
    <script language="JavaScript">
        $(function () {
            $('#my_videos_index').addClass("active");
            $('.get_mini_program_page').click(function () {
                $('#get_mini_program_page').find("textarea").val($(this).data("url"));
                $('#get_mini_program_page').modal('show');
            });
            $(".get_video_covers").click(function () {
                window.location.href = "/qr_code/video?scene=" + $(this).data('id') + "&page=pages/detail/detail&download=true";
            });
            $('.show_share_guide').click(function () {
                $('#show_share_guide').find("img").attr("src", "/qr_code/video?scene=" + $(this).data('id') + "&page=pages/detail/detail");
                $('#show_share_guide').modal('show');
            });
            $('.destroy_video').click(function () {
                var url = $(this).attr("href");
                bootbox.confirm({
                    message: "确认要删除当前视频吗？",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            $.ajax(
                                {
                                    method: "delete",
                                    url: url,
                                    dataType: "json",
                                    async: false,
                                    error: function () {
                                        alert("操作失败");
                                    },
                                    success: function (res) {
                                        if (res && res.code == 0) {
                                            bootbox.alert("已删除", function () {
                                                window.location.reload();
                                            });
                                        }
                                        else {
                                            bootbox.alert("删除失败");
                                        }
                                    }
                                }
                            );
                        }
                    }
                });
                return false;
            });
        });
    </script>
@endsection