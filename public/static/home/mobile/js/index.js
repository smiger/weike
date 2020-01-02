var search_type = -1;

//展开排序菜单
$('.dowm-btn').click(function () {
    $('.dowm-list').toggle();
});

//公告循环
new Swiper("#notice", {
    direction: "vertical",
    autoplay:5000
});

//提现循环
new Swiper("#withdraw", {
    direction: "vertical",
    autoplay:1500
});

new Swiper("#banner", {
    pagination: {
        el: '#banner-pagination',
    },
    autoplay:5000
});

//切换任务类型
$('.daily-task').click(function () {
    var search_type_obj = $("input[name='search_type']");
    $(this).html(search_type_obj.val() == 1?'所有任务':'每日任务');
    search_type_obj.val(search_type_obj.val() == 0?1:0);
    ajaxGetList();
});

//切换排序状态
$('.second-nav li').click(function () {
    var type = $(this).attr('data-type');
    $('.second-nav li').not(this).removeClass('active');
    $(this).addClass('active');
    $("input[name='order_type']").val(type);
    $(this).parent().parent().hide();
    ajaxGetList();
});

//点击上一页
$('.paging-prev').click(function () {
    var page = parseInt($("input[name='page']").val(), 10);
    if(page <= 1){
        message('已经是首页','','error');
        return false;
    }
    $("input[name='page']").val(page-1);
    $("select[name='page']").find("option[value="+ (page-1)+"]").attr("selected",true);

    var data = $('#search_form').serializeObject();
    data.search_type = 0;
    ajaxGetListByData(data);
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
    $("select[name='page']").find("option[value="+ (page+1)+"]").attr("selected",true);

    var data = $('#search_form').serializeObject();
    data.search_type = 0;
    ajaxGetListByData(data);
});

$("select[name='page']").change(function () {
    var page = parseInt($(this).val(), 10);
    $("input[name='page']").val(page);
    $("select[name='page']").find("option[value="+ (page)+"]").attr("selected",true);

    var data = $('#search_form').serializeObject();
    data.search_type = 0;
    ajaxGetListByData(data);
});

//搜索
$('.search-btn').click(function () {
    $("input[name='page']").val('1');
    //$("#search_form").submit();
    ajaxGetList();
});

$("input[name='keyword']").keydown(function(e) {
    if (e.keyCode == 13) {
        $("input[name='page']").val('1');
        //$("#search_form").submit();
        ajaxGetList();
        return false;
    }
});

//获取相关
function ajaxGetList() {
    ajaxGetListByData($('#search_form').serialize());
}

function ajaxGetListByData(data) {
    var loading = message('加载中','','loading');
    $.post(
        '/home/task/index.html',
        data,
        function (ret) {
            if(ret.type == 'success'){
                var html = '';
                $.each(ret.message,function (index,item) {
                    html += '<li>\n' +
                        '                    <a href="/home/task/detail/id/'+item['id']+'.html">\n' +
                        '                        <div class="task-list-title-silver">\n' +
                        '                           <div class="task-list-title">' +item['title']+ '</div>\n' +
                        '                            <span class="task-list-gold">\n' +
                        '                                <span>￥</span>\n' +
                        '                                '+item['unit_price']+'元\n' +
                        '                            </span>\n' +
                        '                        </div>\n' +
                        '                        <span class="task-list-prefix">商</span>\n' +
                        '                        <span class="task-list-id">' +item['id']+ '</span>\n' +
                        '                        <span class="task-jifen">'+item['give_credit1']+'积分</span>\n' +
                        '                        <span class="task-list-remain-num">剩余'+(item['ticket_num']-item['join_num'])+'份</span>\n' +
                        '                        <div class="task-list-progress-content">\n' +
                        '                            <div class="task-list-progress"><span style="width:'+item['percent']+'%"></span></div>\n' +
                        '                            <div class="task-list-progress-num">'+item['percent']+'%</div>\n' +
                        '                        </div>\n' +
                        '                        <div class="task-list-gold-silver">\n' +
                        '                            <span class="task-list-end-time">结束时间：'+item['end_time']+' </span>\n' +
                        '                        </div>\n' +
                        '                    </a>\n' +
                        '                </li>';
                });
                $('.new-task-list ul').html(html);
                $(window).scrollTop($("task-list").scrollTop());
                loading.close();
                return true;
            }
            message(ret.message,ret.redirect,ret.type)
        },'json'
    );
}