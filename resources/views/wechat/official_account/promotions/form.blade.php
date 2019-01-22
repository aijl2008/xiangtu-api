
<div class="form-group {{ $errors->has('original_id') ? 'has-error' : ''}}">
    {!! Form::label('name', '公众号', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-10">
        {!! Form::input('text', 'original_id', null, [
      'class' => 'form-control',
      'placeholder' => '公众号',
    ]) !!}
        {!! $errors->first('original_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', '活动名称', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-10">
        {!! Form::input('text', 'name', null, [
      'class' => 'form-control',
      'placeholder' => '活动名称',
    ]) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('keywords') ? 'has-error' : ''}}">
    {!! Form::label('keywords', '关键语', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-10">
        {!! Form::input('text', 'keywords', null, [
      'class' => 'form-control',
      'placeholder' => '关键语',
    ]) !!}
        {!! $errors->first('keywords', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('tip') ? 'has-error' : ''}}">
    {!! Form::label('tips', '提示语', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-10">
        {!! Form::textarea('tip', null, [
      'class' => 'form-control',
      'placeholder' => '提示语',
    ]) !!}
        {!! $errors->first('tip', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('poster') ? 'has-error' : ''}}">
    {!! Form::label('poster', '海报', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-10">
        {!! Form::input('text', 'poster', null, [
      'class' => 'form-control',
      'placeholder' => '海报'
    ]) !!}
        <input type="file" id="poster_file" name="poster_file">
        {!! $errors->first('poster', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : '保存', ['class' => 'btn btn-primary']) !!}
        <a href="{{ route('wechat.official_account.promotions.index', []) }}" class="btn btn-primary">返回列表</a>
    </div>
</div>
@section('js')
    <script language="JavaScript">
        $(function () {
            $('#admin_classifications_index').addClass("active");
            $('#poster_file').on('change', function () {
                    var formElement = document.querySelector("form");
                    var formData = new FormData(formElement);
                    console.log(formData);
                    $.ajax({
                        url: "{{route('admin.uploader.upload')}}",
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            var files = $('input[name="poster_file"]').prop('files');
                            console.log(files);
                            if (files.length == 0) {
                                bootbox.alert({
                                    message: "请选择图片",
                                    className: 'bb-alternate-modal'
                                });
                                return false;
                            }
                        },
                        success: function (res) {
                            if (res.code == 0) {
                                $("#poster").val(res.data.path);
                            }
                            else {
                                bootbox.alert({
                                    message: res.msg,
                                    className: 'bb-alternate-modal'
                                });
                            }
                        },
                        error: function () {
                            bootbox.alert({
                                message: "上传失败",
                                className: 'bb-alternate-modal'
                            });
                        }
                    });
                }
            );
        });
    </script>
@endsection