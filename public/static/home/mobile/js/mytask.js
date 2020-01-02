//点击上一页
$('.paging-prev').click(function () {
    var page = parseInt($("input[name='page']").val(), 10);
    if(page <= 1){
        message('已经是首页','','error');
        return false;
    }
    $("input[name='page']").val(page-1);
    $("select[name='page']").find("option:selected").prop("selected",false);
    $("select[name='page']").find("option[value="+ (page-1)+"]").prop("selected",true);

    window.location.hash = "page=" + (page-1);

    ajaxGetList();
});

//下一页
$('.paging-next').click(function () {
    var page = parseInt($("input[name='page']").val(), 10);
    var pageCount = parseInt($(this).attr('data-page'), 10);
    if(page >= pageCount){
        message('已经是最后一页','','error');
        return false;
    }
    $("input[name='page']").val(page+1);
    $("select[name='page']").find("option:selected").prop("selected",false);
    $("select[name='page']").find("option[value="+ (page+1)+"]").prop("selected",true);

    window.location.hash = "page=" + (page+1);

    ajaxGetList();
});

$("select[name='page']").change(function () {
    var page = parseInt($(this).val(), 10);
    $("input[name='page']").val(page);
    $("select[name='page']").find("option:selected").prop("selected",false);
    $("select[name='page']").find("option[value="+ (page)+"]").prop("selected",true);

    window.location.hash = "page=" + page;

    ajaxGetList();
});

//获取相关
function ajaxGetList() {
    var loading = message('加载中','','loading');
    $.post(
        window.location.href,
        $('#page_form').serialize(),
        function (ret) {
            if(ret.type == 'success'){
                $('.new-task-list ul').html(ret.message);
                $(window).scrollTop(0);
                loading.close();
                return true;
            }
            message(ret.message,ret.redirect,ret.type)
        },'json'
    );
}

function delMyTask(id) {
    $(document).dialog({
        type : 'confirm',
        titleText: '删除任务',
        content: '确定要删除任务ID为：' + id + '任务吗？',
        onClickConfirmBtn: function(){
            Common.loading.show();
            $.post('/home/mytask/del.html', { id: id }, function(res) {
                Common.loading.hide();
                if (res.type == "success") {
                    $("#delMyTask" + id).parent().parent().parent().remove();
                    return;
                }
                message(res.message,res.redirect,res.type);
            });
        }
    });
}

function check(id){
    return true;
    Common.ApiPost('/home/mytask/check', {id: id}, function(json){
        if(json.code != 0){
            message('没有剩余需要审核的订单','','error');
            return;
        }

        window.location.href = "/home/mytaskaudit/index/id/"+id+".html";
    });
}

function setTop(id){
    if ($("#setTop" + id).hasClass("ajax-ok")) {
        return;
    }

    bootbox.prompt({
        size: "small",
        title: "请输入要置顶的小时数(每小时" + $setting_top_fee + "元，最多" + $setting_top_max_hour + "小时)",
        inputType: 'number',
        buttons: {
            confirm: {
                label: '确定',
                className: 'btn-success'
            },
            cancel: {
                label: '取消',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result == null) {
                return;
            }
            if ($.trim(result) == "") {
                message('输入不能为空','','error');
                return;
            }
            if (result.length > 6) {
                message('请输入合适的范围','','error');
                return;
            }
            //正整数正则表达式
            var regu = /^[1-9]\d*$/;
            if(!regu.test(result)) {
                message('请输入正确的格式','','error');
                return;
            }
            if (result > $setting_top_max_hour) {
                message('置顶时间最多' + $setting_top_max_hour + '小时','','error');
                return;
            }

            Common.ApiPost('/home/mytask/setTop', {id: id, hour: result}, function(res){
                if(res.type == "success"){
                    $("#setTop" + id).addClass("ajax-ok").html("已置顶");
                    message(res.message,'',res.type);
                }else{
                    message(res.message,res.redirect,res.type);
                }
            });
        }
    });
}

function outStockTask(id) {
    if ($("#outStockTask" + id).hasClass("ajax-ok")) {
        return;
    }

    $(document).dialog({
        type : 'confirm',
        titleText: '下架任务',
        content: '确定要下架任务ID为：' + id + '任务吗？',
        onClickConfirmBtn: function(){
            Common.loading.show();
            $.post('/home/mytask/outstock.html', { id: id }, function(res) {
                Common.loading.hide();
                if (res.type == "success") {
                    $("#outStockTask" + id).addClass("ajax-ok").html("已下架");
                    return;
                }
                message(res.message,res.redirect,res.type);
            });
        }
    });
}

function hiddenTask(id, val) {
    if (val == 0) {
        Common.loading.show();
        $.post('/home/mytask/hidden.html', { id: id, hidden: 1 }, function(res) {
            Common.loading.hide();
            if (res.type == "success") {
                $("#hidden0Task" + id).hide();
                $("#hidden1Task" + id).show();
                return;
            }
            message(res.message,res.redirect,res.type);
        });
        return;
    }

    $(document).dialog({
        type : 'confirm',
        titleText: '隐藏任务',
        content: '确定要隐藏任务ID为：' + id + '任务吗？',
        onClickConfirmBtn: function(){
            Common.loading.show();
            $.post('/home/mytask/hidden.html', { id: id, hidden: 0 }, function(res) {
                Common.loading.hide();
                if (res.type == "success") {
                    $("#hidden1Task" + id).hide();
                    $("#hidden0Task" + id).show();
                    return;
                }
                message(res.message,res.redirect,res.type);
            });
        }
    });
}

function parseHashPageChange(page) {
    $("input[name='page']").val(page);
    $("select[name='page']").find("option:selected").prop("selected",false);
    $("select[name='page']").find("option[value="+ (page)+"]").prop("selected",true);
    ajaxGetList();
}

$(function(){
    var parseHash = Common.ParseLocationHash();
    if (parseHash.hasOwnProperty("page")) {
        var page = parseInt(parseHash.page, 10);
        parseHashPageChange(page);
    }
});

window.onhashchange = function() {
    var page = 1;
    var parseHash = Common.ParseLocationHash();
    if (parseHash.hasOwnProperty("page")) {
        page = parseInt(parseHash.page, 10);
    }

    parseHashPageChange(page);
};
