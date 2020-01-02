<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/24
 * Time: 16:18
 */

namespace app\admin\controller;


use app\admin\model\Uploads;
use think\Log;

class Upload extends Base{


    /**
     * 上传单图
     */
    public function file(){
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if(!$info){
            message($file->getError(),'','error');
        }
        $record = [
            'admin_id' => $this->administrator['id'],
            'extension' => $info->getExtension(),
            'save_name' => str_replace('\\','/',$info->getSaveName()),
            'filename' => $info->getFilename(),
            'md5' => $info->hash('md5'),
            'sha1' => $info->hash('sha1'),
            'size' => $info->getSize(),
            'create_time' => TIMESTAMP
        ];
        //数据库存入失败记录日志
        if(!Uploads::addInfo($record)){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$record['save_name'].'数据库记录失败');
        }
        message([
            'path' => $record['save_name'],
            'url' => to_media($record['save_name'])
        ],'','success');
    }
}