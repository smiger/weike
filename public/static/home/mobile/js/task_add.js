$(function () {
    //日期选择插件
    var currYear = (new Date()).getFullYear();
    var opt={
        date:{preset : 'date'},
        datetime:{preset : 'datetime'},
        time:{preset : 'time'},
        default:{
            theme: 'android-ics light', //皮肤样式
            display: 'modal', //显示方式
            mode: 'scroller', //日期选择模式
            lang:'zh',
            dateFormat: 'yy-mm-dd',
            startYear:currYear - 10, //开始年份
            endYear:currYear + 10 //结束年份
        }
    };
    var optDateTime = $.extend(opt['datetime'], opt['default']);
    $("#task_beginTime").mobiscroll(optDateTime).datetime(optDateTime);
    $("#task_endTime").mobiscroll(optDateTime).datetime(optDateTime);

    //任务类别选择
    $('#task-select i').click(function () {
        var category_id = Math.floor($(this).attr('data-id'));
        var credit1 = parseFloat($(this).attr('data-credit1'));
        var credit2 = parseFloat($(this).attr('data-credit2'));
        $("input[name='category_id']").val(category_id);
        if(credit1 > 0){
            $("input[name='give_credit1']").attr('placeholder','不低于'+credit1+'积分');
        }
        if(credit2 > 0){
            $("#unit_price").attr('placeholder','不低于'+credit2+'元');
        }
        $('.t_item_selected').not(this).removeClass('t_item_selected');
        $(this).not('.t_item_selected').addClass('t_item_selected');
    });

    //频率选择
    $('.t_wx').click(function () {
        var rate = Math.floor($(this).attr('data-rate'));
        $('.t_wx_selected').not(this).removeClass('t_wx_selected');
        $(this).not('.t_wx_selected').addClass('t_wx_selected');
        $("input[name='rate']").val(rate);
        if(rate == 2){
            $('#betweenHourdiv').show();
        }else{
            $('#betweenHourdiv').hide();
        }
    });
    
    //是否需要截图
    $('.checkbox-img').click(function () {
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $("input[name='is_screenshot']").val('0');
        }else{
            $(this).addClass('active');
            $("input[name='is_screenshot']").val('1');
        }
    });

    //是否需要限制IP
    $('.checkbox-ip').click(function () {
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $("input[name='is_ip_restriction']").val('0');
            $('#limit-area').hide();
            $('.task-txt option').not(':first-child').attr("selected", false);
        }else{
            $(this).addClass('active');
            $("input[name='is_ip_restriction']").val('1');
            $('#limit-area').show();
        }
    });

    //限速设置
    $('.checkbox-limitMin').click(function () {
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $("input[name='is_limit_speed']").val('0');
            $('#limitmindiv').hide();
            $("input[name='limit_ticket_num']").val('');
        }else{
            $(this).addClass('active');
            $("input[name='is_limit_speed']").val('1');
            $('#limitmindiv').show();
        }
    });

    //输入积分事件
    $("#unit_price").bind('input propertychange', function() {
        var money = parseFloat($(this).val().trim());
        if(isNaN(money)){
            money = 0;
        }
        /*var fee = parseFloat($(this).attr('data-fee'));
        $('#user_gold').html((money*Math.abs((1-fee))).toFixed(2));
        $('#plat_gold').html((money*Math.abs(fee)).toFixed(2));*/
        var task_num = $("#task_num").val();
        if($.trim(task_num)!=""){
            countMoney(money,task_num);
        }
    });

    //校验数量
    $("#task_num").bind('input propertychange', function() {
        var task_num = $(this).val();
        var r = /^[0-9]*[1-9][0-9]*$/;
        if(task_num==""){
            $("#count_money").val("");
            return false;
        }
        if(!r.test(task_num)){
            message('请输入正确的数量！','','error');
            $("#task_num").val("");
            $("#task_num").focus();
            $("#count_money").val("");
            return false;
        }
        var min_number = $("#min_number").val();
        if(parseInt(task_num)<parseInt(min_number)){
            message("数量不能低于"+min_number+"！",'','error');
            $("#task_num").val("");
            $("#task_num").focus();
            $("#count_money").val("");
            return false;
        }
        var unit_price = $("#unit_price").val();
        if(unit_price.trim()!=""&&task_num.trim()!=""){
            countMoney(unit_price,task_num);
        }       
    });

    //点击图片上传
    $('.images-box .item').last().click(function(){
        //点击选择图片
        $('.files input[type="file"]:first').click().bind('change',function(){
            if(this.files.length > 0){
                var src = getFileSrc(this.files[0]);
                $('.images-box').prepend('<div class="item img-item"><img class="images-item" src="'+src+'"></div>');
                //向.files的div中添加input，已经选择了一个，将这个保留，新增一个新的input作为补充
                $('.files').prepend('<input type="file" accept="image/*" name="thumbs[]">');
            }
        });
    });

    //点击图片删除对应的移除input
    $(document).on('click','.images-item',function (event) {
        var index = $('.images-item').index(this);
        $(this).parent().remove();
        //第一个是新添加的，所以要删除第二个
        $('.files input:first').next().remove();
        event.stopPropagation();
    });

    //发布任务
    $('.publish-task-btn').click(function () {
        if($("#task_beginTime").val().trim()==""){
            message('请选择开始时间！','','error');
            return false;
        }
        if($("#task_endTime").val().trim()==""){
            message('请选择结束时间！','','error');
            return false;
        }
        if($("#unit_price").val().trim()==""){
            message('请输入任务单价！','','error');
            return false;
        }
        if($("#task_num").val().trim()==""){
            message('请输入任务数量！','','error');
            return false;
        }
        var check_state = 1;//$("#check_state").val();
        if(check_state==1){
            var checkImgLength = $(".img-item").length;
            if(checkImgLength == 0) {
                 message('请上传审核图样！','','error');
                 return false;
            }           
        }
        var alertMsg = "";
        var stepImgLength = $("#stepUl li").length;
        if(stepImgLength==1){
            //var process_sm = document.getElementsByName("process_sm[]")[0].value;
            var process_sm = $(".step_text").eq(0).val();
            if(process_sm.trim()==""){
                alertMsg = "您未写操作说明!";
            }
        }
        if (alertMsg != "") {
            message(alertMsg,'','error');
            return false;
        }
        /*if(task_state==1){
            var user_rmb_balance = $("#user_rmb_balance").val();
            var count_money = $("#count_money").val();
            if(parseFloat(user_rmb_balance)<parseFloat(count_money)){
                 if (confirm('你账户剩余任务币不足，前往充值？')) {
                    location.href="/api/weixin/mayibangfu/wxPay/goPcPay";
                 }
                 return false;
            }
        }*/
        var link_val = $("#about_url").val().trim();
        if(link_val!=''){
            var regex =/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
            if(!regex.test(link_val)){
                message('请输入正确的链接地址！','','error');
                return false;
            }
            if(link_val.substring(0,3).toLowerCase()!='ftp'&&link_val.substring(0,4).toLowerCase()!='http'&&link_val.substring(0,5).toLowerCase()!='https'){
                message('请输入正确的链接地址！','','error');
                return false;
            }
        }

        var formData = new FormData($( "#post_form" )[0]);
        var ua = navigator.userAgent.toLowerCase();
        if (ua.indexOf('safari') != -1 || ua.indexOf('iphone') != -1 || ua.indexOf('ipad') != -1 || ua.indexOf('ipod') != -1) {
            try {
                var iterator = formData.entries(), end = false;
                while(end == false) {
                    var item = iterator.next();
                    if (item.value != undefined) {
                        var pair = item.value;
                        console.log(pair);
                        if (pair[1] instanceof File) {
                            var files = formData.getAll(pair[0]);
                            formData.delete(pair[0]);
                            jQuery.each(files, function (key, fileNameObject) {
                                if (fileNameObject.name && fileNameObject.size != 0) {
                                    formData.append(pair[0], fileNameObject);
                                }
                            });
                        }
                    } else if (item.done == true) {
                        end = true;
                    }
                }
            } catch (e) {
                console.log(e.message);
            }
        }
        message('正在发布','','loading');
        $.ajax({
            url: post_form_action,
            type: 'POST',
            data: formData,
            dataType: "json",
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                message(res.message,res.redirect,res.type);
            },
            error: function () {
                message('请求错误','','error');
            }
        });
    });
});

    //计算费用
    function countMoney(unit_price,task_num){
        var fee = parseFloat($("#service_charge").val());
        var service_charge = 1 + fee;
        var count_money = parseFloat(unit_price)*parseFloat(task_num);
        count_money = parseFloat(count_money);
        $('#count_money').val((count_money*service_charge).toFixed(2));
        $("#user_gold").html(count_money.toFixed(2));
        $('#plat_gold').html((count_money*Math.abs(fee)).toFixed(2));
    }

    /* 获取元素路径 */
    function getFileSrc(file){
        var url = null;
        if (window.createObjectURL != undefined) {
            url = window.createObjectURL(file)
        } else if (window.URL != undefined) {
            url = window.URL.createObjectURL(file)
        } else if (window.webkitURL != undefined) {
            url = window.webkitURL.createObjectURL(file)
        }
        return url;
    }

    //替换半角逗号
    function checkContent(obj){
        var content = obj.value;
        content.indexOf(',');
        if(content.indexOf(',')!=-1){
            obj.value = content.replaceAll(",","，");
        }
    }
    String.prototype.replaceAll  = function(s1,s2){   
        return this.replace(new RegExp(s1,"gm"),s2);   
    }

    function topPosition(){
        var liHeight = parseInt($("#stepUl li").css("height"));
        //$(".step_bottom_a").css({top:((liHeight - 218)/2 + 184) + "px", marginTop : "0"});
        $(".stepPhotoWrap").css("height",(liHeight - 218) + "px");
    }

    var checkImgN = 0, stepImgN = 0, stepDataN = 0;

    //点击放大图片
    function imgBig(id){
        var thisSrc = $(id).attr("src");
        $(".big_img").attr("src",thisSrc);
        $(".big_img_wrap,.big_img_content").show();
        var thisHeight = parseInt($(".big_img").css("height"));
        $(".big_img").css({ marginTop : -thisHeight/2 + "px"});
    }

    $write_step_pager_select = $("#write_step_pager_select");

    function changePage(obj) {
        var val = $(obj).val();
        $("#stepUl").animate({"marginLeft": (-(scroWidth) * val)});
    }
      
    function  changePrev(id){//上一张
        if(id == 0){
            checkImgN--;
            checkImgN = (checkImgN < 0 ? 0 : checkImgN);
            play(0);
        }else{
            stepImgN--;
            stepImgN = (stepImgN < 0 ? 0 : stepImgN);
            play(1);
        }
    }
    
    function changeNext(id){//下一张
        if(id == 0){        
            var img_num = $("#clearfix li").length; //图片个数
            checkImgN++;
            checkImgN = ( checkImgN > (img_num-1) ? (img_num-1) : checkImgN );
            play(0);
        }else{      
            var img_num = $("#stepUl li").length; //图片个数
            stepImgN++;
            stepImgN = ( stepImgN > (img_num-1) ? (img_num-1) : stepImgN );
            play(1);
        }
    }
    
    function play(id) {//动画移动
        
        if(id == 0){//审核图样
            
            var img_num=$("#clearfix li").length; //图片个数
            if(img_num > 0){
                img = new Image(); //图片预加载
                img.src = $("#clearfix li").eq(checkImgN).find("img").attr("src");
            }
            
            if (img_num > 1) {//大于1个的时候进行移动
                if (checkImgN < (img_num - 1)) { //前1个
                    $("#clearfix").animate({"marginLeft": (-(scroWidth) * (checkImgN < 0 ? 0 : checkImgN))});
                }
                else if (checkImgN >= (img_num - 1)) {//后1个
                    $("#clearfix").animate({"marginLeft": (-(scroWidth) * (img_num - 1))});
                }
            }
            
        }else{//写流程
            var img_num=$("#stepUl li").length; //图片个数
            if(img_num > 0){
                stepImg = new Image(); //图片预加载
                stepImg.src = $("#stepUl li").eq(stepImgN).find("img").attr("src");
            }
            if (img_num > 1) {//大于1个的时候进行移动
                $write_step_pager_select.find("option").removeAttr("selected");
                if (stepImgN < (img_num - 1)) { //前1个
                    $("#stepUl").animate({"marginLeft": (-(scroWidth) * (stepImgN < 0 ? 0 : stepImgN))});
                    $write_step_pager_select.find("option[value='" + stepImgN + "']").prop("selected", true);
                }
                else if (stepImgN >= (img_num - 1)) {//后1个
                    $("#stepUl").animate({"marginLeft": (-(scroWidth) * (img_num - 1))});
                    $write_step_pager_select.find("option[value='" + (img_num - 1) + "']").prop("selected", true);
                }
            }
        }    
    }

    //写操作步骤开始    
    //写操作步骤
    function uploadStepData(){
        $(".write_step_wrap").show();       
        var stepLength = $("#stepUl li").length;
        var addBtn = $(".add_step").length;
        if(addBtn == 0){
            $("#stepWrap" + (stepLength-1)).append('<div class="add_step" onclick="addStep();">添加步骤</div></div>');
        }
    }

    function returnCreateStep(){//写操作步骤取消
        $(".main_wrap").show(); 
        $(".write_step_wrap").hide();       
        var stepLength = $("#stepUl li").length;                
        for(var i=0;i<stepLength;i++){
            $(".each_step" + (i+1)).remove();
            stepDataN--;
        }           
        $("#stepUl").css({width:scroWidth + "px",marginLeft:"0"});      
        $("#stepText0").val('');
        $("#stepFileWrap0 img").attr("src","/static/home/mobile/images/img.png");          
        $("#stepFileWrap0").css({width:"70px"});
        $("#stepFileWrap0 img , #uploadImgData0").css({width:"70px",height:"70px"});
        $("#resultImg0").removeClass('uploadImg');//移除大图属性
        $("#stepMsg0").removeClass('content_hide');//显示“上传图片几个字”
        $(".step_bottom_a").css({display:"none"});
        $write_step_pager_select.hide();
        topPosition();

        if (stepDataN > 0) {
            $("#addStepData").html("已有" + stepDataN + "个步骤，编辑操作说明");
        } else {
            $("#addStepData").html("添加操作说明");
        }
    }

    function finishStep(){//写操作步骤完成
        var stepImgLength = $("#stepUl li").length;
        //var process_sm = document.getElementsByName("process_sm[]")[stepImgLength-1].value;
        var process_sm = $(".step_text").eq(stepImgLength-1).val();
        if(stepImgLength==1&&process_sm.trim()==""){
            if(process_sm.trim()==""){
                 message('请填写操作步骤！','','error');
                 return false;
            }
        }else{
            if(process_sm.trim()==""){
                 message('请填写操作说明文字！','','error');
                 return false;
            }               
        } 
        $(".main_wrap").show();
        $(".upload_check_wrap").hide();
        $("#stepUl").css({marginLeft:"0"});
        stepImgN=0;
        $(".add_step").remove();//先清除 “添加步骤”按钮      
        $(".write_step_wrap").hide();  

        stepDataN = $("#stepUl li").length;
        if (stepDataN > 0) {
            $("#addStepData").html("已有" + stepDataN + "个步骤，编辑操作说明");
        } else {
            $("#addStepData").html("添加操作说明");
        }
    }    
           
    //写操作步骤图片上传
    function stepSetImagePreviews(id) {
        
        var docObj = document.getElementById("uploadImgData" + id);

        if (docObj.files && docObj.files[0]) {
             
            $("#resultImg" + id).attr("src",window.URL.createObjectURL(docObj.files[0]));

        }else {
            //IE下，使用滤镜
            docObj.select();
            var imgSrc = document.selection.createRange().text;
            console.log(imgSrc);
            var localImagId = document.getElementById("resultImg" + id);
            //必须设置初始大小
            localImagId.style.width = "160px";
            localImagId.style.height = "320px";
            //图片异常的捕捉，防止用户修改后缀来伪造图片
            try {
                localImagId.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
                localImagId.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;
            }
            catch (e) {
                message('您上传的图片格式不正确，请重新选择!','','error');
                return false;
            }
            document.selection.empty();
        }                 
        $("#resultImg" + id).addClass('uploadImg');
        $("#stepFileWrap" + id + "  input[type=file]").css({width:"160px",marginLeft:"-80px"});
        $("#stepMsg" + id).addClass('content_hide'); 
        return true;
    }
  
    //添加步骤
    function addStep(){
        var stepImgLength = $("#stepUl li").length;
        //var process_sm = document.getElementsByName("process_sm[]")[stepImgLength-1].value; 
        var process_sm = $(".step_text").eq(stepImgLength-1).val();
        if(process_sm.trim()==""){
             message('请填写操作说明文字！','','error');
             return false;
        }               
        $("#stepUl .clear").remove();
        $(".add_step").remove();
        var stepLength, i,result;
        stepLength = $(".each_step").length;                    
        i = parseInt(stepLength) + 1;//当前添加的步骤数
        $(".step_bottom_a").css({display:"block"});
        $write_step_pager_select.show();
        var imgWrapHeight = parseInt($("#stepPhotoWrap" + (i-2)).css("height"));
        $("#stepUl").css({Width:scroWidth * i + "px" , height:imgWrapHeight+"px"});
        
        //阿拉伯数字转换为简写汉字转换
        try {
            result = Arabia_To_SimplifiedChinese(i);
        } catch (e) {
            console.error(e);
        }

        var html = '';
        html += '<li class="each_step' + (i-1) + '">';
        html += '<div class="each_step"><div class="step_intro">';   
        html += '<div class="stepWrap" id="stepWrap' + (i-1) + '">'; 
        html += '<div class="step_title"  id="step_title' + (i-1) + '">步骤' + result +'<span class="del' + (i-1) + '"   onclick="delStep(' + (i-1) + ');">删除</span></div>';
        html += '<div class="add_step" onclick="addStep();">添加步骤</div>';
        html += '</div>';
        html += '<textarea id="process_sm" name="process_sm[]" onblur="checkContent(this);" maxlength="1000" id="stepText' + (i-1) + '" class="step_text" placeholder="请输入操作说明文字"></textarea></div>';
        html += '<div class="step_photo_wrap  stepPhotoWrap"  id="stepPhotoWrap' + (i-1) + '"><div class="file_wrap stepFileWrap" id="stepFileWrap' + (i-1) + '">';       
        html += '<img src="/static/home/mobile/images/img.png" id="resultImg' + (i-1) + '"/>'; 
        html += '<input type="file" name="processFile[]" class="stepInput"  id="uploadImgData' + (i-1) + '"  onchange="stepSetImagePreviews(' + (i-1) + ');" accept="image/*" />';        
        html += '<div class="upload_msg stepMsg" id="stepMsg' + (i-1) + '">上传图片</div>'; 
        html += '</div></div></div></li><div class="clear"></div>';
     
        $("#stepUl").append(html);      
        $("#stepUl li").css({width:scroWidth + "px"});
        $("#stepUl").css({width:scroWidth * i + "px"});
        stepImgN++;
        play(1);
        $("#stepNum").val(i);
        topPosition();

        $write_step_pager_select.find("option").removeAttr("selected");
        $write_step_pager_select.append('<option value="' + stepImgN + '" selected="selected">步骤' + result + '</option>');
    }
    
    //删除步骤
    function delStep(id){       
        var stepLength = $(".each_step").length;
        
        if(id == 0 && stepLength == 1){//仅有一步,清空文本框，删除已经上传的图片
            
            $(".operate_step_id").remove();
            $("#stepText0").attr("name", "process_sm[]").val('');
            $("#stepFileWrap0 img").attr("src","/static/home/mobile/images/img.png");          
            $("#stepFileWrap0").css({width:"5.25rem"});
            $("#stepFileWrap0 img , #uploadImgData0").css({width:"5.25rem",height:"5.25rem"});
            $("#uploadImgData0").attr("name", "processFile[]");
            $("#resultImg" + id).removeClass('uploadImg');//移除大图属性
            $("#stepMsg0").removeClass('content_hide');//显示“上传图片几个字”
            $(".step_bottom_a").css({display:"none"});
            $write_step_pager_select.hide();
                
        }else if(id > 0 && id == (stepLength-1)){//删除的是最后一步(不是唯一一步)，需要把最后一步的添加步骤按钮移至倒数第二步           
            $(".each_step" + id).remove();
            $("#stepUl").css({width: scroWidth * (stepLength-1) , marginLeft:-scroWidth *(stepLength-2)}); 
            stepImgN--;
            id--;
            $("#stepWrap" + id).append('<div class="add_step" onclick="addStep();">添加步骤</div></div>');
        
        }else{//删除的是中间的一步，所有步骤序号重新排列
            $(".each_step" + id).remove();
            $("#stepUl").css({width: scroWidth * (stepLength-1) , marginLeft:-scroWidth *(stepLength-2)}); 
            for(var i=0;i<(stepLength-1);i++){
                
                $("#stepUl li").eq(i).attr("class","each_step" + i);        
                $(".stepWrap").eq(i).attr("id","stepWrap" + i);
                
                var j = parseInt(i) + 1;
                //阿拉伯数字转换为简写汉字转换
                try {
                    var result = Arabia_To_SimplifiedChinese(j);
                } catch (e) {
                    console.error(e);
                }
              
                //步骤部分
                $(".step_title").eq(i).attr("id","step_title" + i);
                $(".step_title").eq(i).html('步骤' + result + '<span class="del' + i + '"   onclick="delStep(' + i + ');">删除</span>');
                //文本框  
                $(".step_intro textarea").eq(i).attr("id","stepText" + i);            
                $(".stepPhotoWrap").eq(i).attr("id","stepPhotoWrap" + i);//图片外围
                
                $(".stepFileWrap img").eq(i).attr("id","resultImg" + i);//中心图片
                
                //input相关
                $(".stepFileWrap").eq(i).attr("id","stepFileWrap" + i);
                $(".stepInput").eq(i).attr("id","uploadImgData" + i);
                $(".stepInput").eq(i).attr("onchange","stepSetImagePreviews(" + i + ")");
                  
                $(".stepMsg").eq(i).attr("id","stepMsg" + i);//上传图片文字
            }           
        }
        topPosition();
    }
    //写操作步骤结束

