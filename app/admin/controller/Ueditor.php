<?php
namespace app\admin\controller;


use app\admin\model\Uploads;
use think\Log;
use ueditor\Uploader;

class Ueditor extends Base
{

    //配置信息
    public $config = [
        'imageActionName' => 'uploadimage',
        'imageFieldName' => 'upfile',
        'imageMaxSize' => 2048000,
        'imageAllowFiles' =>[ 0 => '.png',1 => '.jpg', 2 => '.jpeg', 3 => '.gif', 4 => '.bmp',],
        'imageCompressEnable' => true,
        'imageCompressBorder' => 1600,
        'imageInsertAlign' => 'none',
        'imageUrlPrefix' => '',
        'imagePathFormat' => '/uploads/{yyyy}{mm}{dd}/{time}{rand:6}',


        'scrawlActionName' => 'uploadscrawl',
        'scrawlFieldName' => 'upfile',
        'scrawlPathFormat' => '/uploads/{yyyy}{mm}{dd}/{time}{rand:6}',
        'scrawlMaxSize' => 2048000,
        'scrawlUrlPrefix' => '',
        'scrawlInsertAlign' => 'none',


        'snapscreenActionName' => 'uploadimage',
        'snapscreenPathFormat' => '/uploads/{yyyy}{mm}{dd}/{time}{rand:6}',
        'snapscreenUrlPrefix' => '',
        'snapscreenInsertAlign' => 'none',


        'catcherLocalDomain' =>[0 => '127.0.0.1', 1 => 'localhost', 2 => 'img.baidu.com'],
        'catcherActionName' => 'catchimage',
        'catcherFieldName' => 'source',
        'catcherPathFormat' => '/uploads/{yyyy}{mm}{dd}/{time}{rand:6}',
        'catcherUrlPrefix' => '',
        'catcherMaxSize' => 2048000,
        'catcherAllowFiles' =>[ 0 => '.png', 1 => '.jpg', 2 => '.jpeg', 3 => '.gif', 4 => '.bmp'],


        'videoActionName' => 'uploadvideo',
        'videoFieldName' => 'upfile',
        'videoPathFormat' => '/uploads/{yyyy}{mm}{dd}/{time}{rand:6}',
        'videoUrlPrefix' => '',
        'videoMaxSize' => 102400000,
        'videoAllowFiles' =>[0 => '.flv', 1 => '.swf', 2 => '.mkv', 3 => '.avi', 4 => '.rm', 5 => '.rmvb', 6 => '.mpeg', 7 => '.mpg', 8 => '.ogg', 9 => '.ogv', 10 => '.mov', 11 => '.wmv', 12 => '.mp4', 13 => '.webm', 14 => '.mp3', 15 => '.wav', 16 => '.mid'],


        'fileActionName' => 'uploadfile',
        'fileFieldName' => 'upfile',
        'filePathFormat' => '/uploads/{yyyy}{mm}{dd}/{time}{rand:6}',
        'fileUrlPrefix' => '',
        'fileMaxSize' => 51200000,
        'fileAllowFiles' =>[0 => '.png', 1 => '.jpg', 2 => '.jpeg', 3 => '.gif', 4 => '.bmp', 5 => '.flv', 6 => '.swf', 7 => '.mkv', 8 => '.avi', 9 => '.rm', 10 => '.rmvb', 11 => '.mpeg', 12 => '.mpg', 13 => '.ogg', 14 => '.ogv', 15 => '.mov', 16 => '.wmv', 17 => '.mp4', 18 => '.webm', 19 => '.mp3', 20 => '.wav', 21 => '.mid', 22 => '.rar', 23 => '.zip', 24 => '.tar', 25 => '.gz', 26 => '.7z', 27 => '.bz2', 28 => '.cab', 29 => '.iso', 30 => '.doc', 31 => '.docx', 32 => '.xls', 33 => '.xlsx', 34 => '.ppt', 35 => '.pptx', 36 => '.pdf', 37 => '.txt', 38 => '.md', 39 => '.xml'],


        'imageManagerActionName' => 'listimage',
        'imageManagerListPath' => '/uploads/',
        'imageManagerListSize' => 20,
        'imageManagerUrlPrefix' => '',
        'imageManagerInsertAlign' => 'none',
        'imageManagerAllowFiles' =>[0 => '.png', 1 => '.jpg', 2 => '.jpeg', 3 => '.gif', 4 => '.bmp'],
        'fileManagerActionName' => 'listfile',
        'fileManagerListPath' => '/uploads/',
        'fileManagerUrlPrefix' => '',
        'fileManagerListSize' => 20,
        'fileManagerAllowFiles' =>[0 => '.png', 1 => '.jpg', 2 => '.jpeg', 3 => '.gif', 4 => '.bmp', 5 => '.flv', 6 => '.swf', 7 => '.mkv', 8 => '.avi', 9 => '.rm', 10 => '.rmvb', 11 => '.mpeg', 12 => '.mpg', 13 => '.ogg', 14 => '.ogv', 15 => '.mov', 16 => '.wmv', 17 => '.mp4', 18 => '.webm', 19 => '.mp3', 20 => '.wav', 21 => '.mid', 22 => '.rar', 23 => '.zip', 24 => '.tar', 25 => '.gz', 26 => '.7z', 27 => '.bz2', 28 => '.cab', 29 => '.iso', 30 => '.doc', 31 => '.docx', 32 => '.xls', 33 => '.xlsx', 34 => '.ppt', 35 => '.pptx', 36 => '.pdf', 37 => '.txt', 38 => '.md', 39 => '.xml']
    ];


    //ueditor入口
    public function index(){
        error_reporting(E_ERROR);
        header("Content-Type: text/html; charset=utf-8");
        $action = trim(params('action'));
        switch ($action) {
            case 'config':
                $result =  json_encode(json_decode(json_encode($this->config)));
                break;

            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $result = $this->_upload($action);
                break;

            /* 列出图片 */
            case 'listimage':
                $result = $this->_list($action);
                break;
            /* 列出文件 */
            case 'listfile':
                $result = $this->_list($action);
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = $this->_crawler();
                break;

            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
        }

        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }


    /**
     * 上传附件和上传视频
     * User: Jinqn
     * Date: 14-04-09
     * Time: 上午10:17
     */
    private function _upload($action){
        /* 上传配置 */
        $base64 = "upload";
        switch (htmlspecialchars($action)) {
            case 'uploadimage':
                $config = [
                    "pathFormat" => $this->config['imagePathFormat'],
                    "maxSize" => $this->config['imageMaxSize'],
                    "allowFiles" => $this->config['imageAllowFiles']
                ];
                $fieldName = $this->config['imageFieldName'];
                break;
            case 'uploadscrawl':
                $config = [
                    "pathFormat" => $this->config['scrawlPathFormat'],
                    "maxSize" => $this->config['scrawlMaxSize'],
                    "allowFiles" => $this->config['scrawlAllowFiles'],
                    "oriName" => "scrawl.png"
                ];
                $fieldName = $this->config['scrawlFieldName'];
                $base64 = "base64";
                break;
            case 'uploadvideo':
                $config = [
                    "pathFormat" => $this->config['videoPathFormat'],
                    "maxSize" => $this->config['videoMaxSize'],
                    "allowFiles" => $this->config['videoAllowFiles']
                ];
                $fieldName = $this->config['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $config = [
                    "pathFormat" => $this->config['filePathFormat'],
                    "maxSize" => $this->config['fileMaxSize'],
                    "allowFiles" => $this->config['fileAllowFiles']
                ];
                $fieldName = $this->config['fileFieldName'];
                break;
        }

        /* 生成上传实例对象并完成上传 */
        $up = new Uploader($fieldName, $config, $base64);

        /* 返回数据 */
        $data = $up->getFileInfo();
        $this->_addInfo($data);
        return json_encode($data);

    }


    /**
     * 获取已上传的文件列表
     * User: Jinqn
     * Date: 14-04-09
     * Time: 上午10:17
     */
    private function _list($action){
        /* 判断类型 */
        switch ($action) {
            /* 列出文件 */
            case 'listfile':
                $allowFiles = $this->config['fileManagerAllowFiles'];
                $listSize = $this->config['fileManagerListSize'];
                $path = $this->config['fileManagerListPath'];
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $allowFiles = $this->config['imageManagerAllowFiles'];
                $listSize = $this->config['imageManagerListSize'];
                $path = $this->config['imageManagerListPath'];
        }
        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        /* 获取参数 */
        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end = $start + $size;

        /* 获取文件列表 */
        $path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "":"/") . $path;
        $files = $this->_getfiles($path, $allowFiles);
        if (!count($files)) {
            return json_encode(array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => count($files)
            ));
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
            $list[] = $files[$i];
        }

        /* 返回数据 */
        $result = json_encode(array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => count($files)
        ));

        return $result;

    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    private function _getfiles($path, $allowFiles, &$files = array())
    {
        if (!is_dir($path)) return null;
        if(substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->_getfiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
                        $files[] = array(
                            'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                            'mtime'=> filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }

    /**
     * 抓取远程图片
     * User: Jinqn
     * Date: 14-04-14
     * Time: 下午19:18
     */
    private function _crawler(){
        set_time_limit(0);
        /* 上传配置 */
        $config = array(
            "pathFormat" => $this->config['catcherPathFormat'],
            "maxSize" => $this->config['catcherMaxSize'],
            "allowFiles" => $this->config['catcherAllowFiles'],
            "oriName" => "remote.png"
        );
        $fieldName = $this->config['catcherFieldName'];

        /* 抓取远程图片 */
        $list = array();
        if (isset($_POST[$fieldName])) {
            $source = $_POST[$fieldName];
        } else {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl) {
            $item = new Uploader($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            $data = [
                "state" => $info["state"],
                "url" => $info["url"],
                "size" => $info["size"],
                "title" => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source" => htmlspecialchars($imgUrl)
            ];
            array_push($list, $data);
            $this->_addInfo($data);
        }

        /* 返回抓取数据 */
        return json_encode(array(
            'state'=> count($list) ? 'SUCCESS':'ERROR',
            'list'=> $list
        ));
    }

    //保存文件信息
    private function _addInfo($info){
        $record = [
            'admin_id' => !empty($this->administrator['id'])?$this->administrator['id']:0,
            'extension' => !empty($info['type'])?str_replace('.','',$info['type']):'',
            'save_name' => !empty($info['url'])?str_replace('/uploads/','',$info['url']):'',
            'filename' => !empty($info['title'])?$info['title']:'',
            'size' => !empty($info['size'])?$info['size']:0,
            'create_time' => TIMESTAMP
        ];
        //数据库存入失败记录日志
        if(!Uploads::addInfo($record)){
            Log::error(__FILE__.':'.__LINE__.' 错误：'.$record['save_name'].'数据库记录失败');
        }
    }
}