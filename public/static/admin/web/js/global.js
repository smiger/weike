/**
 * 简单的消息提示
 * @param message
 * @param redirect
 * @param type
 */
function message(message,redirect,type) {
    $('.spop-container').remove();
    var  opt = {
        template: message,
        position  : 'top-center',
        style: type ,
        autoclose: 3000,
        onOpen: function () {},
        onClose: function() {
            if(redirect != undefined){
                if(redirect == 'reload'){
                    window.location.reload();
                }else if(redirect != ''){
                    window.location.href = redirect;
                }
            }
        }
    };
    if(type == 'progress'){
        opt.style = 'info';
        opt.autoclose = false;
        opt.template = '当前上传的进度：'+message+'%';
    }
    //返回对象
    return spop(opt);
}

/**
 * 复制内容
 * @param obj
 * 将data-copy的值绑定到剪切板，且必须包含类名 clipboards
 */
function copy(obj) {
    var value  = $(obj).attr('data-copy');
    if(value){
        if(!window.hasOwnProperty('clipboard')){
            window['clipboard'] = new Clipboard('.clipboards', {
                text: function() {
                    return value;
                }
            });
            window['clipboard'].on('success', function(e) {
                message('复制成功','','success')
            });

            window['clipboard'].on('error', function(e) {
                message('复制失败','','error')
            });
        }
    }
}

/**
 * 初始化图片预览插件
 */
function initImageViewer() {
    var obj = $('[data-magnify]');
    if(obj.length>1){
        obj.magnify({
            headToolbar: [
                'close'
            ],
            footToolbar: [
                'zoomIn',
                'zoomOut',
                'prev',
                'fullscreen',
                'next',
                'actualSize',
                'rotateRight'
            ],
            title: false
        });
    }
}


//初始化图片预览插件
initImageViewer();

