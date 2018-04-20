yii2-sms

基于yii2开发的发送手机短信组件。

Usage
```php
return [   
    'components' => [
        'sms' => [
            'class' => Sms::class,
            'url' => 'http://www.smswang.net:7803/sms',
            'as sms' => SmsBehavior::class,
        ],
    ],
];
```



