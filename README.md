yii2-sms

基于yii2开发的通用手机短信组件，使用简单，易于扩展。

Usage
```php
return [   
    'components' => [
        'sms' => [
            // 短信验证码
            'class' => CaptchaSms::class,
            'url' => 'http://www.smswang.net:7803/sms',
            'account' => '666666',
            'password' => '666666',
            'sign' => 'your sign',
            'as sms' => yii\sms\SmsBehavior::class,
        ],
    ],
];
```

```php
使用事例：
\Yii::$app->sms->send(
    13168755555,
    '短信内容',
    \Yii::$app->request->userIP
);

```


