<div class="modal fade" id="modal-delete" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    ×
                </button>
                <h4 class="modal-title">对话框</h4>
            </div>
            <div class="modal-body">
                <p class="lead">
                    <i class="fa fa-question-circle fa-lg"></i>
                    确认要删除吗?
                </p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input name="_method" type="hidden" value="DELETE">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-times-circle"></i> 确认
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        var dom = $('<input>').attr('type', 'hidden').attr('name', 'id').attr('id', 'truncate');
        $('.delete').click(function () {
            var url = $(this).attr('href');
            $('#modal-delete').modal('show').find("form").attr("action", url).find('#truncate').remove();
            return false;
        });
        $('.truncate').click(function () {
            var ids = [];
            $("td input:checked").each(function () {
                ids.push($(this).val());
            });
            if (ids.length < 1) {
                alert("请选中要删除的记录");
                return false;
            }
            var url = $(this).attr('href');
            dom.val(ids.join(","));
            $('#modal-delete').modal('show').find("form").attr("action", url).append(dom);
            return false;

        });
    });
</script>