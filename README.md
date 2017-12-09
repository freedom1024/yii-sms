yii2-sms

基于yii2开发的通用手机短信组件，已集成阿里大于，优易网短信等主流短信供应商，支持总账号发送和子账号发送方式，使用简单，易于扩展。

Usage
```php
return [   
    'components' => [
        'sms' => [
            // 优易网验证码
            'class' => UeCaptchaSms::class,
            'url' => 'http://www.smswang.net:7803/sms',
            'action' => 'send',
            'account' => '666666',
            'password' => '666666',
            'extno' => '666666',
            'sign' => 'your sign',
            'as sms' => yii\sms\SmsBehavior::class,
        ],
        'subSms' => [
            // 优易网营销短信
            'class' => UeSms::class,
        ],
    ],
];

OR

return [
    'components' => [
        'sms' => [
            // 阿里大于
            'class' => AlidayuSms::class,
            'appkey' => '666666',
            'secretKey' => '666666',
            'sign' => 'your sign',
            'templates' => [
            'verification' => 'SMS_15435041',
            ],
            'as sms' => yii\sms\SmsBehavior::class,
         ],
        'subSms' => [
            // 优易网营销短信
            'class' => UeSms::class,
        ],		
    ],
];

```

```php
总账号发送
\Yii::$app->sms->send(
    13168755555,
    '短信内容',
    Sms::PROJECT_CST_PC,
    \Yii::$app->request->userIP
);

子账号发送
\Yii::$app->subSms->getSmsInstance($shopId)->send(
   13168755555,
   '短信内容',
   Sms::PROJECT_CST_PC,
   \Yii::$app->request->userIP
);
```


