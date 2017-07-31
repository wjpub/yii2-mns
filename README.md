# yii2-mns

## SMS
### conf: 
        'mns' => [
            'class' => 'wjpub\yii2mns\Mns',
            'accessId' => 'ACCESS_ID',
            'accessKey' => 'ACCESS_KEY',
            'endPoint' => 'END_POIND',
            'pointName' => 'TOPIC_NAME(eg:sms.topic-cn-beijing)'
        ],

### use 
        Yii::$app->mns->sendSms(
            SIGN_NAMW,      //签名
            TEMPLATE_CODE,  //模板代码
            PHONE,          //手机号
            PARAMS,         //配置信息，key,value必须都为字符串
        );