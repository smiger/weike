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
                $('.new-task-list').html(ret.message);
                $(window).scrollTop(0);
                loading.close();
                return true;
            }
            message(ret.message,ret.redirect,ret.type)
        },'json'
    );
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
