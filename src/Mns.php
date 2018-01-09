<?php
namespace wjpub\yii2mns;
ini_set("display_errors", "on");

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;

Config::load();

class Mns extends \yii\base\Component
{
    public $endPoint = '';
    public $accessId = '';
    public $accessKey = '';
    public $region = 'cn-hangzhou';
    public $product = "Dysmsapi";
    public $domain = "dysmsapi.aliyuncs.com";

    private $client = null;

    public function init()
    {
        $this->endPoint = 'cn-hangzhou';
        parent::init();
        $profile = DefaultProfile::getProfile($this->region, $this->accessId, $this->accessKey);
        // 增加服务结点
        DefaultProfile::addEndpoint($this->endPoint, $this->region, $this->product, $this->domain);
        $this->client = new DefaultAcsClient($profile);
    }


    public function sendSms($signName, $templateCode, $phone, $params) {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 必填，设置短信接收号码
        $request->setPhoneNumbers((string)$phone);
        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName($signName);
        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($templateCode);
        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode(self::stringParams($params), JSON_UNESCAPED_UNICODE));

        // 发起访问请求
        try
        {
            $res = $this->client->getAcsResponse($request);

            if ($res->Code == 'OK') {
                return $res->RequestId;
            } else {
                return json_encode($res);
            }
        }
        catch (Exception $e)
        {
            return $e;
        }
    }

    public static function stringParams($params)
    {
        $data = [];
        foreach ($params as $key => $value) {
            $data[(string)$key] = (string)$value;
        }
        return $data;
    }

}
?>