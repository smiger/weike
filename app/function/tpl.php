<?php

/**
 * @param array $params
 * 单图上传
 */
function tpl_upload_image($params = []){
    //加上默认选项
    $params += [
        'title' => '缩略图',
        'name' => 'thumb',
        'value' => '',
        'placeholder' => '请选择上传的图片',
        'help' => '建议尺寸：1:1'
    ];
    $thumb = '/static/admin/web/images/nopic.jpg';
    if(!empty($params['value'])){
        $thumb = to_media($params['value']);
    }
    $tpl = '';
    if (!defined('TPL_UPLOAD_IMAGE')) {
        if(!defined('TPL_UPLOAD_IMAGES')){
            $tpl.='
            <link rel="stylesheet" href="/static/plugins/webuploader-0.1.5/webuploader.css">
            <script src="/static/plugins/webuploader-0.1.5/webuploader.min.js"></script>';
        }
        $tpl.='
            <div style="display: none" id="upload_image_fMif7lVjbpgwgvcC"></div>
            <script type="text/javascript">
            //上传文件
            var upload_image_obj = {};
            var uploader_image = WebUploader.create({
               swf: \'/static/plugins/webuploader-0.1.5/Uploader.swf\',
               server: \'' . U("upload/file") . '\',
               pick: {
                   id: \'#upload_image_fMif7lVjbpgwgvcC\',
                   multiple:false
               },
               resize: false,
               auto: true,
               duplicate : true,
               accept: {
                  title: \'选择图片\',
                  extensions: \'gif,jpg,jpeg,bmp,png\',
                  mimeTypes: \'image/*\'
               }
            });
            //上传中文件处理
               uploader_image.on( \'uploadProgress\', function( file, percentage ) {
                  message(percentage,\'\',\'loading\');
               });
               //上传成功后处理
               uploader_image.on( \'uploadSuccess\', function(file ,response ) {
                   message(response.type == \'success\'?\'上传成功\':response.message,\'\',response.type);
                   upload_image_obj.parent().prev().val(response.message[\'path\']);
                   upload_image_obj.parent().parent().next().find(\'img\').attr(\'src\',response.message[\'url\']);
               });
            //单图上传删除图片
            function upload_image_delete(obj){
                $(obj).prev().attr(\'src\',\'/static/admin/web/images/nopic.jpg\');
                $(obj).parent().prev().find(\'input\').val("");
            }
            //单图上传选择图片
            function upload_image_select(obj){
               upload_image_obj = $(obj);
               $("input[name=\'file\']:not([multiple])").click();
            }
            </script>';
        define('TPL_UPLOAD_IMAGE',true);
    }
    $tpl .= '
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">'.$params['title'].'</label>
            <div class="col-sm-8 col-xs-12">
                <div class="input-group ">
                    <input type="text" name="'.$params['name'].'" value="'.$params['value'].'" class="form-control" autocomplete="off" placeholder="'.$params['placeholder'].'">
                    <span class="input-group-btn">
                       <button class="btn btn-default" type="button" onclick="upload_image_select(this)">选择图片</button>
                    </span>
                </div>
                <div class="input-group " style="margin-top:.5em;">
                   <img src="'.$thumb.'" onerror="this.src=\'/static/admin/web/images/nopic.jpg\'; this.title=\'图片未找到.\'" class="img-responsive img-thumbnail" width="150">
                   <em class="close" style="position:absolute; top: 0; right: -14px;" title="删除这张图片" onclick="upload_image_delete(this)">×</em>
                </div>
                <span class="help-block">'.$params['help'].'</span>
            </div>
        </div>';
    echo $tpl;
}


/**
 * @param array $params
 * 多图上传
 */
function tpl_upload_images($params = []){
    //加上默认选项
    $params += [
        'title' => '多图上传',
        'name' => 'thumbs',
        'value' => []
    ];
    $tpl = '';
    if (!defined('TPL_UPLOAD_IMAGES')) {
        if(!defined('TPL_UPLOAD_IMAGE')){
            $tpl.='
            <link rel="stylesheet" href="/static/plugins/webuploader-0.1.5/webuploader.css">
            <script src="/static/plugins/webuploader-0.1.5/webuploader.min.js"></script>';
        }
        $tpl .= '
            <div style="display: none" id="upload_images_fMif7lVjbpgwgvcC"></div>
            <script type="text/javascript">
                //上传文件
                var upload_images_obj = {};
                var uploader_images = WebUploader.create({
                     swf: \'/static/plugins/webuploader-0.1.5/Uploader.swf\',
                     server: \'' . U("upload/file") . '\',
                     pick: {
                         id: \'#upload_images_fMif7lVjbpgwgvcC\',
                         multiple:true
                     },
                     resize: false,
                     auto: true,
                     duplicate : true,
                     accept: {
                         title: \'选择图片\',
                         extensions: \'gif,jpg,jpeg,bmp,png\',
                         mimeTypes: \'image/*\'
                     }
                });
                //上传中文件处理
                uploader_images.on( \'uploadProgress\', function( file, percentage ) {
                     message(percentage,\'\',\'loading\');
                });
                //上传成功后处理
                uploader_images.on( \'uploadSuccess\', function(file ,response ) {
                     message(response.type == \'success\'?\'上传成功\':response.message,"",response.type);
                     upload_images_obj.parent().parent().next().append(
                         \'<div class="multi-item">\' +
                              \'<img src="\'+response.message["url"]+\'" class="img-responsive img-thumbnail">\' +
                              \'<input type="hidden" name="'.$params['name'].'[]" value="\'+response.message["path"]+\'">\' +
                              \'<em class="close" title="删除这张图片" onclick="upload_images_delete(this)">×</em>\'+
                          \'</div>\');
                });
                //上传多图
                function upload_images_select(obj) {
                    upload_images_obj = $(obj);
                    $("input[name=\'file\'][multiple=\'multiple\']").click();
                }
                //删除图片
                function upload_images_delete(obj){
                    $(obj).parent().remove();
                }
        </script>';
        define('TPL_UPLOAD_IMAGES',true);
    }
    $tpl .= '
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">'.$params['title'].'</label>
            <div class="col-sm-8 col-xs-12">
                <div class="input-group">
                    <input type="text" class="form-control" readonly="readonly" placeholder="批量上传图片" autocomplete="off">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" onclick="upload_images_select(this);">选择图片</button>
                    </span>
                </div>
                <div class="input-group multi-img-details">';
    //遍历图片
    if(check_array($params['value'])){
        foreach($params['value'] as $thumb){
            $tpl .= '<div class="multi-item">
                        <img onerror="this.src=\'/static/admin/web/images/nopic.jpg\'; this.title=\'图片未找到\'" src="'.to_media($thumb).'" class="img-responsive img-thumbnail">
                        <input type="hidden" name="'.$params['name'].'[]" value="'.$thumb.'">
                        <em class="close" title="删除这张图片" onclick="upload_images_delete(this)">×</em>
                     </div>';
        }
    }

    $tpl .='
                </div>
                <span class="help-block">按住ctrl键或鼠标拖动选择多张图片</span>
            </div>
        </div>
    ';
    echo $tpl;
}


/**
 * @param array $params
 * @param array $config
 * 百度富文本编辑器
 */
function tpl_ueditor($params = [],$config = []){
    //加上默认选项
    $params += [
        'title' => '详情',
        'name' => 'detail',
        'value' => ''
    ];
    if(!empty($params['placeholder']) && is_string($params['placeholder'])){
        $config['initialContent'] = $params['placeholder'];
    }
    $tpl = '';
    if (!defined('TPL_UEDITOR')) {
        $tpl .= '<script type="text/javascript" src="/static/plugins/ueditor/ueditor.config.js"></script>
                 <script type="text/javascript" src="/static/plugins/ueditor/ueditor.all.js"></script>';
        define('TPL_UEDITOR',true);
    }
    $id = str_replace(['[',']'],"_",$params['name']);
    $tpl .= '<div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">'.$params['title'].'</label>
                <div class="col-sm-8 col-xs-12">
                     <textarea id="'.$id.'" name="'.$params['name'].'">'.$params['value'].'</textarea>
                </div>
            </div>
            <script type="text/javascript">
                 var '.$id.' = UE.getEditor(\''.$id.'\','.json_encode($config).');
            </script>';
    echo $tpl;
}


/**
 * @param array $params
 * @param bool $time
 * 日期范围选择
 */
function tpl_date_range($params = [], $time = false) {
    //加上默认选项
    $params += [
        'title' => '添加时间',
        'name' => 'create_time',
        'start_time' => '',
        'end_time' => ''
    ];
    $tpl = '';
    if (!$time && !defined('TPL_INIT_DATERANGE_DATE')) {
        $tpl = '
        <link rel="stylesheet" type="text/css" href="/static/plugins/daterangepicker/daterangepicker.css" />
        <script type="text/javascript" src="/static/plugins/daterangepicker/moment.js"></script>
        <script type="text/javascript" src="/static/plugins/daterangepicker/daterangepicker.js"></script>
        <script type="text/javascript">
            $(function(){
                    $(".daterange.daterange-date").each(function(){
                        var elm = this;
                        $(this).daterangepicker({
                            startDate: $(elm).prev().prev().val(),
                            endDate: $(elm).prev().val(),
                            format: "YYYY-MM-DD"
                        }, function(start, end){
                            $(elm).find(".date-title").html(start.toDateStr() + " 至 " + end.toDateStr());
                            $(elm).prev().prev().val(start.toDateStr());
                            $(elm).prev().val(end.toDateStr());
                        });
                    });
                });
        </script>';
        define('TPL_INIT_DATERANGE_DATE', true);
    }
    if ($time && !defined('TPL_INIT_DATERANGE_TIME')) {
        $tpl = '
        <link rel="stylesheet" type="text/css" href="/static/plugins/daterangepicker/daterangepicker.css" />
        <script type="text/javascript" src="/static/plugins/daterangepicker/moment.js"></script>
        <script type="text/javascript" src="/static/plugins/daterangepicker/daterangepicker.js"></script>
        <script type="text/javascript">
            $(function(){
                    $(".daterange.daterange-time").each(function(){
                        var elm = this;
                        $(this).daterangepicker({
                            startDate: $(elm).prev().prev().val(),
                            endDate: $(elm).prev().val(),
                            format: "YYYY-MM-DD HH:mm",
                            timePicker: true,
                            timePicker12Hour : false,
                            timePickerIncrement: 1,
                            minuteStep: 1
                        }, function(start, end){
                            $(elm).find(".date-title").html(start.toDateTimeStr() + " 至 " + end.toDateTimeStr());
                            $(elm).prev().prev().val(start.toDateTimeStr());
                            $(elm).prev().val(end.toDateTimeStr());
                        });
                    });
                });
        </script>';
        define('TPL_INIT_DATERANGE_TIME', true);
    }
    $start_time = !$time ? date('Y-m-d',$params['start_time']?$params['start_time']:TIMESTAMP) : date('Y-m-d H:i',$params['start_time']?$params['start_time']:TIMESTAMP);
    $end_time = !$time ? date('Y-m-d',$params['end_time']?$params['end_time']:TIMESTAMP) : date('Y-m-d H:i',$params['end_time']?$params['end_time']:TIMESTAMP);
    $tpl .= '
    <div class="form-group">
         <label class="col-xs-12 col-sm-3 col-md-2 control-label">'.$params['title'].'</label>
         <div class="col-sm-8 col-md-8 col-lg-8 col-xs-12">
              <input name="'.$params['name'] . '[start]'.'" type="hidden" value="'. $start_time.'" />
              <input name="'.$params['name'] . '[end]'.'" type="hidden" value="'. $end_time.'" />
              <button class="btn btn-default daterange '.($time ? 'daterange-time' : 'daterange-date').'" type="button"><span class="date-title">'.$start_time.' 至 '.$end_time.'</span> <i class="fa fa-calendar"></i></button>
         </div>
	</div>';
    echo $tpl;
}



/**
 * @param array $params
 * 日期选择
 */
function tpl_date($params = []) {
    //加上默认选项
    $params += [
        'title' => '添加时间',
        'name' => 'create_time',
        'value' => ''
    ];
    $tpl = '';
    if (!defined('TPL_INIT_DATE')) {
        $tpl = '
        <link rel="stylesheet" type="text/css" href="/static/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" />
        <script type="text/javascript" src="/static/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript" src="/static/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.zh-CN.js"></script>
        <script type="text/javascript">
            $(function(){
                    $(".datetime").datetimepicker({
                        format: \'yyyy-mm-dd hh:ii\',
                        language:  \'zh-CN\', 
                        autoclose:true
                    });
            });
        </script>';
        define('TPL_INIT_DATE', true);
    }
    $value = date('Y-m-d H:i',$params['value']?$params['value']:TIMESTAMP);
    $tpl .= '
    <div class="form-group">
         <label class="col-xs-12 col-sm-3 col-md-2 control-label">'.$params['title'].'</label>
         <div class="col-sm-8 col-md-8 col-lg-8 col-xs-12">
            <input size="16" name="'.$params['name'].'" type="text" value="'.$value.'" readonly class="form-control datetime">
         </div>
	</div>';
    echo $tpl;
}


/**
 * @param array $params
 * @return string
 * 地区选择
 */
function tpl_area($params = []) {
    //加上默认选项
    $params += [
        'title' => '所在地区',
        'name' => 'area',
        'province' => '',
        'city' => '',
        'district' => '',
    ];
    $tpl = '';
    if (!defined('TPL_AREA')) {
        $tpl .= '
        <script type="text/javascript" src="/static/plugins/district.js"></script>
		<script type="text/javascript">
			$(function() {
                  $(".tpl-district-container").each(function(){
                        var elms = {};
                        elms.province = $(this).find(".tpl-province")[0];
                        elms.city = $(this).find(".tpl-city")[0];
                        elms.district = $(this).find(".tpl-district")[0];
                        var vals = {};
                        vals.province = $(elms.province).attr("data-value");
                        vals.city = $(elms.city).attr("data-value");
                        vals.district = $(elms.district).attr("data-value");
                        window.Districts.render(elms, vals, {withTitle: true});
                    });
			});
		</script>';
        define('TPL_AREA', true);
    }
    $tpl .= '
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">'.$params['title'].'</label>
            <div class="col-sm-8 col-xs-12">
                <div class="row row-fix tpl-district-container">
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <select name="' . $params['name'] . '[province]" data-value="' . $params['province'] . '" class="form-control tpl-province"></select>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <select name="' . $params['name'] . '[city]" data-value="' . $params['city'] . '" class="form-control tpl-city"></select>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <select name="' . $params['name'] . '[district]" data-value="' . $params['district'] . '" class="form-control tpl-district"></select>
                    </div>
                </div>
            </div>
        </div>';
    return $tpl;
}





/**
 * @param string $message
 * 错误提示信息
 * 输出并退出
 */
function tpl_mobile_message($message = '糟糕，出错了',$url = ''){
    $href = "history.back();";
    if(!empty($url)){
        $href = "location.href='{$url}'";
    }
    exit('<html lang="zh-cn"><head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
            <title>糟糕，出错了</title>
            <meta name="format-detection" content="telephone=no, address=no">
            <meta name="apple-mobile-web-app-capable" content="yes"> <!-- apple devices fullscreen -->
            <meta name="apple-touch-fullscreen" content="yes">
            <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
            <meta name="keywords" content="郑州慕马树网络科技有限公司，河南郑州微信第三方开发，河南郑州小程序开发，河南郑州游戏开发，河南郑州手机app定制开发，河南郑州网站建设，河南郑州服务器维护等，全心全意服务，客户至上，售后无忧，0371-55297759">
            <meta name="description" content="郑州慕马树网络科技有限公司，河南郑州微信第三方开发，河南郑州小程序开发，河南郑州游戏开发，河南郑州手机app定制开发，河南郑州网站建设，河南郑州服务器维护等，全心全意服务，客户至上，售后无忧，0371-55297759">
            <link href="/static/error/mobile/common.min.css" rel="stylesheet">
        </head>
        <body style="padding-bottom: 20px;">
        <div class="mui-content fadeInUpBig animated mui-backdrop" style="background-color: rgb(239, 239, 244);">
            <div class="mui-content-padded">
                <div class="mui-message">
                    <div class="mui-message-icon t2"><span class="mui-msg-error"></span></div>
                    <h4 class="title">'.$message.'</h4>
                    <p class="mui-desc"></p>
                    <div class="mui-button-area" onclick="'.$href.'">
                        <a href="javascript:;" class="mui-btn mui-btn-success mui-btn-block">确定</a>
                    </div>
                </div>
            </div>
        </div>
        </body>
        </html>');
}