<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/19
 * Time: 16:46
 */
namespace app\home\model;

class Area extends Base{

    //所有省份
    public static $provinces = [
        '北京', '天津', '河北', '山西', '内蒙古', '辽宁', '吉林', '黑龙江',
        '上海', '江苏', '浙江', '安徽', '福建', '江西', '山东', '河南', '湖北',
        '湖南', '广东', '海南', '广西', '甘肃', '陕西', '新疆', '青海', '宁夏',
        '重庆', '四川', '贵州', '云南', '西藏', '台湾', '澳门', '香港'
    ];

}