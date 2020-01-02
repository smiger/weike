/**
 *
 * 信息提示
 * @param message
 * @param redirect
 * @param type
 * @returns {*|jQuery}
 */
function message(message,redirect,type) {
    $('.dialog').remove();
    var opt = {
        type : 'toast',
        infoIcon: '/static/plugins/dialog/images/icon/'+type+'.png',
        infoText: message,
        autoClose: 3000,
        onClosed:function () {
            if(redirect == 'reload'){
                window.location.reload();
            }else if(redirect != undefined && redirect != ''){
                window.location.href = redirect;
            }
        }
    };
    if(type == 'loading'){
        opt.infoIcon = '/static/plugins/dialog/images/icon/loading.gif';
        opt.autoClose = 0;
    }
    //返回对象
    return $(document).dialog(opt);
}

/**
 * 复制内容
 * @param obj
 * 将data-copy的值绑定到剪切板
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

//阿拉伯数字转换为简写汉字转换
function Arabia_To_SimplifiedChinese(Num) {
    for (i = Num.length - 1; i >= 0; i--) {
        Num = Num.replace(",", "")//替换Num中的“,”
        Num = Num.replace(" ", "")//替换Num中的空格
    }
    if (isNaN(Num)) { //验证输入的字符是否为数字
        //alert("请检查小写金额是否正确");
        return;
    }
    //字符处理完毕后开始转换，采用前后两部分分别转换
    part = String(Num).split(".");
    newchar = "";
    //小数点前进行转化
    for (i = part[0].length - 1; i >= 0; i--) {
        if (part[0].length > 10) {
            //alert("位数过大，无法计算");
            return "";
        }//若数量超过拾亿单位，提示
        tmpnewchar = ""
        perchar = part[0].charAt(i);
        switch (perchar) {
            case "0":  tmpnewchar = "零" + tmpnewchar;break;
            case "1": tmpnewchar = "一" + tmpnewchar; break;
            case "2": tmpnewchar = "二" + tmpnewchar; break;
            case "3": tmpnewchar = "三" + tmpnewchar; break;
            case "4": tmpnewchar = "四" + tmpnewchar; break;
            case "5": tmpnewchar = "五" + tmpnewchar; break;
            case "6": tmpnewchar = "六" + tmpnewchar; break;
            case "7": tmpnewchar = "七" + tmpnewchar; break;
            case "8": tmpnewchar = "八" + tmpnewchar; break;
            case "9": tmpnewchar = "九" + tmpnewchar; break;
        }
        switch (part[0].length - i - 1) {
            case 0: tmpnewchar = tmpnewchar; break;
            case 1: if (perchar != 0) tmpnewchar = tmpnewchar + "十"; break;
            case 2: if (perchar != 0) tmpnewchar = tmpnewchar + "百"; break;
            case 3: if (perchar != 0) tmpnewchar = tmpnewchar + "千"; break;
            case 4: tmpnewchar = tmpnewchar + "万"; break;
            case 5: if (perchar != 0) tmpnewchar = tmpnewchar + "十"; break;
            case 6: if (perchar != 0) tmpnewchar = tmpnewchar + "百"; break;
            case 7: if (perchar != 0) tmpnewchar = tmpnewchar + "千"; break;
            case 8: tmpnewchar = tmpnewchar + "亿"; break;
            case 9: tmpnewchar = tmpnewchar + "十"; break;
        }
        newchar = tmpnewchar + newchar;
    }
    //替换所有无用汉字，直到没有此类无用的数字为止
    while (newchar.search("零零") != -1 || newchar.search("零亿") != -1 || newchar.search("亿万") != -1 || newchar.search("零万") != -1) {
        newchar = newchar.replace("零亿", "亿");
        newchar = newchar.replace("亿万", "亿");
        newchar = newchar.replace("零万", "万");
        newchar = newchar.replace("零零", "零");
    }
    //替换以“一十”开头的，为“十”
    if (newchar.indexOf("一十") == 0) {
        newchar = newchar.substr(1);
    }
    //替换以“零”结尾的，为“”
    if (newchar.lastIndexOf("零") == newchar.length - 1) {
        newchar = newchar.substr(0, newchar.length - 1);
    }
    return newchar;
}

/**
 * 对Date的扩展，将 Date 转化为指定格式的String
 * 月(M)、日(d)、12小时(h)、24小时(H)、分(m)、秒(s)、周(E)、季度(q) 可以用 1-2 个占位符
 * 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
 * eg:
 * (new Date()).pattern("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
 * (new Date()).pattern("yyyy-MM-dd E HH:mm:ss") ==> 2009-03-10 二 20:09:04
 * (new Date()).pattern("yyyy-MM-dd EE hh:mm:ss") ==> 2009-03-10 周二 08:09:04
 * (new Date()).pattern("yyyy-MM-dd EEE hh:mm:ss") ==> 2009-03-10 星期二 08:09:04
 * (new Date()).pattern("yyyy-M-d h:m:s.S") ==> 2006-7-2 8:9:4.18

使用：(eval(value.replace(/\/Date\((\d+)\)\//gi, "new Date($1)"))).pattern("yyyy-M-d h:m:s.S");
 */
Date.prototype.pattern = function(fmt) {
    var o = {
    "M+" : this.getMonth()+1, //月份
    "d+" : this.getDate(), //日
    "h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12, //小时
    "H+" : this.getHours(), //小时
    "m+" : this.getMinutes(), //分
    "s+" : this.getSeconds(), //秒
    "q+" : Math.floor((this.getMonth()+3)/3), //季度
    "S" : this.getMilliseconds() //毫秒
    };
    var week = {
    "0" : "/u65e5",
    "1" : "/u4e00",
    "2" : "/u4e8c",
    "3" : "/u4e09",
    "4" : "/u56db",
    "5" : "/u4e94",
    "6" : "/u516d"
    };
    if(/(y+)/.test(fmt)){
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    }
    if(/(E+)/.test(fmt)){
        fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "/u661f/u671f" : "/u5468") : "")+week[this.getDay()+""]);
    }
    for(var k in o){
        if(new RegExp("("+ k +")").test(fmt)){
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
        }
    }
    return fmt;
}

$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$.fn.formFieldValues = function(data) {
    var els = this.find(':input').get();

    $.each(els, function() {
        if (this.name && data[this.name]) {
            var names = data[this.name];
            var $this = $(this);
            if(Object.prototype.toString.call(names) !== '[object Array]') {
                names = [names]; //backwards compat to old version of this code
            }
            if(this.type == 'checkbox' || this.type == 'radio') {
                var val = $this.val();
                var found = false;
                for(var i = 0; i < names.length; i++) {
                    if(names[i] == val) {
                        found = true;
                        break;
                    }
                }
                $this.attr("checked", found);
            } else {
                $this.val(names[0]);
            }
        }
    });
    return this;
};

var Common = {
    onscroll: true,
    LoginUser: null,
    RUNTIME: {IsDebug: false, PagerCount: 0, RequestCompleted: true, traditional: false},
    loadElememt: $("<div id=\"popFail\"><div class=\"bk\"></div><div class=\"cont\"><img src=\"data:image/gif;base64,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\" alt=\"loading...\"><span>正在加载...</span></div></div>"),
    loading: {
        show: function() {
            $("body").append(Common.loadElememt);
        },
        hide: function() {
            Common.loadElememt.remove();
        }
    }, //loading
    Valid: {
        getResult: function(validate, value, param) {
            if(!Common.Valid[validate].validator(value, param)) {
                Common.tipMsg(Common.Valid[validate].message);
                return true;
            }
            return false;
        },
        //移动手机号码验证
        mobile: {
            validator: function(value) {
                var reg = /^1[3|4|5|7|8|9]\d{9}$/;
                return reg.test(value);
            },
            message: '输入手机号码格式不正确.'
        },
        email: {
            validator: function(value) {
                var reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                return reg.test(value);
            },
            message: '输入邮箱格式不正确.'
        },
        qq: {
            validator: function(value) {
                var reg = /^\d{6,10}$/;
                return reg.test(value);
            },
            message: '输入QQ号码格式不正确.'
        },
        less: {
            validator: function(value, param) {
                validateBox.less.message = param.message;
                var length = param.length;
                return value.length >= length;
            }
        },
        equal: {
            validator: function(value, param) {
                validateBox.equal.message = param.message;
                var equalId = param.equalId
                return value == $(equalId).val();
            }
        },
        num: {
            validator: function(value) {
                var reg = /^[0-9]*$/;
                return reg.test(value);
            },
            message: '输入数字格式不正确.'
        },
        money: {
            validator: function(value) {
                var reg = /^\d{0,4}(\.\d{0,2})?$/;
                return reg.test(value);
            },
            message: '输入金额格式不正确.'
        }
    },
    Config: {
        isIE7: false,
        ServerKey: 'bxka_',
        WebSite: '',
        ServerPath: 'http://' + window.location.host
    },
    ApiPageList: function(path, datas, page, pageSize, scrollLoadFun, callbackFun, errorFun) {
        var m = this.RUNTIME;
        datas.pageNo = page;
        datas.pageSize = pageSize;
        this.loadData(path, datas, "GET", function(json, header) {
            window.setTimeout(function() {
                if (callbackFun) {
                    callbackFun(json, header.Pagination);
                }
            }, 10);
            if(!m.scrollLoadBind) {
                Common.scrollLoadData(scrollLoadFun);
            }
        }, errorFun);
    },
    ApiGet: function(path, datas, callbackFun, errorFun) {
        this.loadData(path, datas, "GET", callbackFun, errorFun);
    },
    ApiPost: function(path, datas, callbackFun, errorFun) {
        this.loadData(path, datas, "POST", callbackFun, errorFun);
    },
    ApiPostJson: function(path, datas, callbackFun, errorFun) {
        this.loadData(path, datas, "POST", callbackFun, errorFun, "application/json");
    },
    ApiPut: function(path, datas, callbackFun, errorFun) {
        this.loadData(path, datas, "PUT", callbackFun, errorFun);
    },
    ApiDelete: function(path, datas, callbackFun, errorFun) {
        this.loadData(path + "?" + $.param(datas), {}, "DELETE", callbackFun, errorFun);
    },
    ApiGetDetail: function(data) {
        return {
            detail: data
        };
    },
    loadData: function(path, datas, requestType, callbackFun, errorFun, contentType) {
        this.loadDataAjax(this.Config.ServerPath + path, datas, requestType, callbackFun, errorFun, contentType);
    },
    loadDataAjax: function(path, datas, requestType, callbackFun, errorFun, contentType) {
        /*if (!Common.RUNTIME.RequestCompleted) {
            console.log("上次请求未完成");
            return;
        }*/

        if (path.indexOf("?") != -1)
            path += "&";
        else
            path += "?";
        path += "__t=" + Math.random();

        requestType = requestType || 'GET';

        if (contentType == "application/json" && typeof (datas) != "string")
        {
            datas = Common.converParmJson(datas);
        }

        var loginUser = Common.getLoginUserJson();

        $.ajax({
            type: requestType,
            url: path,
            data: datas,
            dataType: "json",
            contentType: contentType,
            timeout: 300000,
            success: function(json, textStatus, jqXHR) {
                if (json.hasOwnProperty('code') && json.code != 0) {
                    Common._xhrError(json, json.code, json.message, "", errorFun);
                    return;
                }

                Common._xhrSuccess(json, jqXHR, callbackFun);
            },
            beforeSend: function(request) {
                if(loginUser.hasOwnProperty('token')) {
                    request.setRequestHeader("X-Access-Token", loginUser.token);
                }

                Common.loading.show();
                Common.RUNTIME.RequestCompleted = false;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //console.log(XMLHttpRequest);
                Common._xhrError(XMLHttpRequest.responseJSON, XMLHttpRequest.status, textStatus, errorThrown, errorFun);
            }
        });
    }, //loadDataAjax
    _xhrSuccess: function(json, xhr, callbackFun) {
        var header = {
            AuthSession: {},
            Pagination: {}
        };
        var authSession = xhr.getResponseHeader("X-AuthSession");
        var pagination = xhr.getResponseHeader("X-Pagination");
        if (authSession && authSession != '') {
            header.AuthSession = JSON.parse(authSession);
        }
        if (pagination && pagination != '') {
            header.Pagination = JSON.parse(pagination);
        }

        Common.loading.hide();
        Common.RUNTIME.RequestCompleted = true;

        window.setTimeout(function() {
            if (callbackFun) {
                callbackFun(json, header);
            }
        }, 10);
    },
    _xhrError: function(json, status, textStatus, errorThrown, errorFun) {
        console.log(JSON.stringify(json));
        console.log(status);
        console.log(textStatus);
        console.log(errorThrown);
        var errorMsg = '', _errorMsg = "获取数据失败,请重新尝试";
        if(json && json.hasOwnProperty("message")) {
            errorMsg = json.message.replace(/\r\n/g, "<br />");
        } else {
            errorMsg = _errorMsg;
        }
        var code = 0;
        if(json && json.hasOwnProperty("code")) {
            code = json.code;
        }
        
        Common.loading.hide();
        Common.RUNTIME.RequestCompleted = true;

        switch(status) {
            case 409:
            case 500:
                errorMsg = _errorMsg;
                break;

            case 404:
                errorMsg = "您浏览的内容不存在";
                Common.tipMsg(errorMsg, "温馨提示", function() {
                });
                return;
                break;
            case 400:
                break;
            default:
        }

        window.setTimeout(function() {
            if (errorFun) {
                errorFun(json);
            } else {
                Common.tipMsg(errorMsg);
            }
        }, 10);
    },
    _xhrAbort: function() {
        try {
            if (Common.xhr) {
                if (Common.xhr.readyState != 4) {
                    Common.xhr.abort();
                }
                Common.xhr = null;
            }

            /*if (Common.RUNTIME.IsDebug) {
                Common.tipMsg("Common.xhr.abort()", "调试信息");
            }*/
        } catch(e) {
            Common.tipMsg(e.message, "温馨提示");
        }
    },
    tipMsg: function(errorMsg) {
        alert(errorMsg);
    },
    scrollLoadData: function(callback, totalPage) {
        if (typeof(app) == 'undefined' && typeof(Bnr) == 'undefined') {
            return;
        }

        var m = app.RUNTIME;
        m.num = m.num || 0;
        if(Common.RUNTIME.scrollLoadBind) return;

        $(document).ready(function() {
            var range = 150; //距下边界长度/单位px
            var totalheight = 0;
            m.num = 1;
            //$(window).scroll(function() {
            $scroll = $(window);
            $scroll.unbind('scroll');
            $scroll.bind('scroll', function() {
                if (Common.onscroll) {
                    var srollPos = $scroll.scrollTop(); //滚动条距顶部距离(页面超出窗口的高度)
                    totalheight = parseFloat($scroll.height()) + parseFloat(srollPos);
                    if (($(document).height() - range) <= totalheight && m.more > 0 && m.loaded) {
                        m.num++;
                        if (callback) {
                            m.loaded = false;
                            callback(m.num, true);
                        }
                    }
                }
            });
        });

        Common.RUNTIME.scrollLoadBind = true;
    },
    loadImg: function(src, callback) {
        var img = new Image();
        img.onload = callback;
        img.src = src;
    }, //loadImg
    GetLength: function(str) {
        ///<summary>获得字符串实际长度，中文2，英文1</summary>
        ///<param name="str">要获得长度的字符串</param>
        var realLength = 0,
            len = str.length,
            charCode = -1;
        for (var i = 0; i < len; i++) {
            charCode = str.charCodeAt(i);
            if (charCode >= 0 && charCode <= 128) realLength += 1;
            else realLength += 2;
        }
        return realLength;
    },
    cutstr: function(str, len) { //js截取字符串，中英文都能用  @param str：需要截取的字符串 @param len: 需要截取的长度
        if (typeof(str) === 'undefined') return;
        var str_length = 0;
        var str_len = 0;
        str_cut = new String();
        str_len = str.length;
        for (var i = 0; i < str_len; i++) {
            a = str.charAt(i);
            str_length++;
            if (escape(a).length > 4) {
                //中文字符的长度经编码之后大于4
                str_length++;
            }
            str_cut = str_cut.concat(a);
            if (str_length > len) {
                str_cut = str_cut.concat("...");
                return str_cut;
            }
        }
        //如果给定字符串小于指定长度，则返回源字符串；
        if (str_length <= len) {
            return str;
        }
    },
    converParmJson: function (parm)
    {
        if (!parm) return "null";
        var o = parm, result = {};
        if ($.isArray(o))
        {
            for (var i = 0; i < o.length; i++)
            {
                result[o[i].name] = o[i].value;
            }
        } else
        {
            result = o;
        }
        return JSON.stringify(result);
    },
    getlocalStorage: function(key) {
        var val = unescape(window.localStorage[Common.Config.ServerKey + key]);
        val = (val != 'undefined' ? val : '{}');

        try {
            return JSON.parse(val);
        } catch(e) {
            return val;
        }
    },
    setlocalStorage: function(key, val) {
        val = (val != 'undefined' ? val : '');
        val = JSON.stringify(val);
        window.localStorage.setItem(Common.Config.ServerKey + key, escape(val));
    },
    dellocalStorage: function(key) {
        window.localStorage.removeItem(Common.Config.ServerKey + key);
    },
    CheckUserHasLogin: function(isGo) {
        if(typeof(isGo) === 'undefined') isGo = true;
        var loginUser = Common.getLoginUserJson();
        if(!loginUser.isLogin && isGo) {
            window.clicked('login.html');
        }
        return loginUser.isLogin;
    },
    getLoginUserJson: function() {
        Common.LoginUser = {};

        try {
            var loginUser = Common.getlocalStorage("loginUser");
            if (loginUser.user) {
                Common.LoginUser = loginUser;

                Common.LoginUser.isLogin = true;
            } else {
                Common.LoginUser = {'isLogin': false};
            }
        } catch (e) {
            Common.LoginUser = {'isLogin': false};
        }

        return Common.LoginUser;
    },
    clearLoginUser: function() {
        Common.dellocalStorage("loginUser");
    },
    getAuthRequestHeader: function() {
        var requestHeaders = {};
        var loginUser = Common.getLoginUserJson();
        if(loginUser.hasOwnProperty('Authorization')) {
            requestHeaders["X-Access-Token"] = loginUser.Authorization;
        }
        return requestHeaders;
    },
    GetRequest: function() {
        var url = decodeURIComponent(location.search); //获取url中"?"符后的字串
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            strs = str.split("&");
            for (var i = 0; i < strs.length; i++) {
                theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
            }
        }
        return theRequest;
    },
    ParseLocationHash: function() {
        var url = decodeURIComponent(location.hash);
        var theRequest = new Object();
        var str = url.substr(1);
        strs = str.split("&");
        for (var i = 0; i < strs.length; i++) {
            theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
        }
        return theRequest;
    }
};

function DateFormatter(value, format) {
    if (value == null || value == '') {
        return '';
    }
    var dt;
    if (value instanceof Date) {
        dt = value;
    } else {
        dt = new Date(value * 1000);
    }
    return dt.pattern(format);
}
