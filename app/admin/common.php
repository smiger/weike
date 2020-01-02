<?php
function U($url = '', $vars = '', $suffix = true, $domain = false) {
    $module = 'admin/';

    $controller = \think\Loader::parseName(request()->controller());
    if ('' == $url) {
        // 空字符串输出当前的 模块/控制器/操作
        $url = $module . $controller . '/' . request()->action();
    }

    $path       = explode('/', $url);
    $action     = \think\Config::get('url_convert') ? strtolower(array_pop($path)) : array_pop($path);
    $controller = empty($path) ? $controller : (\think\Config::get('url_convert') ? \think\Loader::parseName(array_pop($path)) : array_pop($path));
    $module     = empty($path) ? $module : array_pop($path) . '/';
    $url        = $module . $controller . '/' . $action;

    return url($url, $vars, $suffix, $domain);
}

/**
 * @Title: authcheck
 * @Description: todo(权限节点判断)
 * @param string $rule
 * @param int $uid
 * @param string $relation
 * @param string $t
 * @param string $f
 * @return string
 * @author duqiu
 * @date 2016-5-24
 */
function authcheck($rule, $uid, $relation='or', $t='', $f='noauth'){
    $auth = new \expand\Auth();
    if( $auth->check($rule, $uid, $type=1, $mode='url',$relation) ){
        $result = $t;
    }else{
        $result = $f;
    }
    return $result;
}

function menuActive($rule, $level = 0) {
    $rule = strtolower($rule);

    $path = strtolower(CONTROLLER_PARSENAME . '/' . ACTION_PARSENAME);
    if ($path == $rule) {
        return true;
    }

    $path_array = [
        'post_category', 'post', 'save', 'authgroup'
    ];
    $path = strtolower(CONTROLLER_PARSENAME . '/' . str_replace($path_array, 'index', ACTION_PARSENAME));
    if ($path == $rule) {
        return true;
    }

    $path = strtolower(CONTROLLER_PARSENAME);
    if ($path == $rule) {
        return true;
    }

    $path = strtolower('/' . CONTROLLER_PARSENAME . '/');
    if (strpos($path, '/' . $rule . '/') !== false) {
        return true;
    }

    $path = strtolower(CONTROLLER_PARSENAME);
    if (strpos($path, $rule) !== false && $level == 1) {
        return true;
    }

    return false;
}
