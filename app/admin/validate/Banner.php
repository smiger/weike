<?php
namespace app\admin\validate;

class Banner extends Base {

    //验证规则
    protected $rule = [
        'title|标题' => 'require',
        'thumb|轮播图' => 'require',
        'url|链接' => 'require',
        'order_by|排序' => 'number',
        'is_display|状态' => 'in:0,1'
    ];

}