<?php
/**
 * Created by pan.
 * User: pan
 * Date: 2017/11/14
 * Time: 9:41
 */

//[b]命名空间为alisms[/b]
namespace alisms;

//引入sdk的命名空间
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;

//引入autoload.php，EXTEND_PATH就是指tp5根目录下的extend目录，系统自带常量。alisms为我们复制api_sdk过来后更改的目录名称
require_once EXTEND_PATH.'alisms/vendor/autoload.php';
Config::load();             //加载区域结点配置


class SendSms
{
    //关键的配置，我们用成员属性
    public $accessKeyId = null; //阿里云短信获取的accessKeyId
    public $accessKeySecret = null; //阿里云短信获取的accessKeySecret
    public $signName = null;    //短信签名，要审核通过
    public $templateCode = null;    //短信模板ID，记得要审核通过的

    public function send($mobile,$templateParam)
    {
        //获取成员属性
        $accessKeyId     = $this->accessKeyId;
        $accessKeySecret = $this->accessKeySecret;
        $signName        = $this->signName;
        $templateCode    = $this->templateCode;
        //短信API产品名（短信产品名固定，无需修改）
        $product = "Dysmsapi";
        //短信API产品域名（接口地址固定，无需修改）
        $domain = "dysmsapi.aliyuncs.com";
        //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
        $region = "cn-hangzhou";

        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        // 增加服务结点
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        // 初始化AcsClient用于发起请求
        $acsClient= new DefaultAcsClient($profile);

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($mobile);

        // 必填，设置签名名称
        $request->setSignName($signName);

        // 必填，设置模板CODE
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数
        if($templateParam) {
            $request->setTemplateParam(json_encode($templateParam));
        }

        //发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);

        //返回请求结果，这里为为数组格式
        $result = json_decode(json_encode($acsResponse),true);
        return $result;
    }

}
