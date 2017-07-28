<?php
namespace deepwise\yii2mns;

require_once('mns-autoloader.php');

use Yii;
use AliyunMNS\Client;
use AliyunMNS\Topic;
use AliyunMNS\Constants;
use AliyunMNS\Model\MailAttributes;
use AliyunMNS\Model\SmsAttributes;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\PublishMessageRequest;

class Mns extends \yii\base\Component
{
    public $endPoint = '';
    public $accessId = '';
    public $accessKey = '';

    private $client = null;
    private $topic = null;

    const SMS_TOPIC = 'sms.topic-cn-beijing';

    public function init()
    {
        parent::init();
        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
        $this->topic = $this->client->getTopicRef(self::SMS_TOPIC);
    }

    public function sendSms($signName, $templateCode, $phone, $params)
    {
        $batchSmsAttributes = new BatchSmsAttributes($signName, $templateCode);
        $batchSmsAttributes->addReceiver((string)$phone, $this->stringParams($params));
        //$batchSmsAttributes->addReceiver((string)'15811312907', ["code" => "123444"]);
        $messageAttributes = new MessageAttributes(array($batchSmsAttributes));
        $messageBody = "text";
        $request = new PublishMessageRequest($messageBody, $messageAttributes);
        try
        {
            $res = $this->topic->publishMessage($request);
            if ($res->isSucceed()) {
                return $res->getMessageId();
            } else {
                return false;
            }
        }
        catch (MnsException $e)
        {
            return false;
        }
    }

    public function stringParams($params)
    {
        $data = [];
        foreach ($params as $key => $value) {
            $data[(string)$key] = (string)$value;
        }
        return $data;
    }

}
?>