yii2-sms

基于yii2开发的手机短信组件。

Usage
```php
return [   
    'components' => [
        'sms' => [
            'class' => CaptchaSms::class,
            'url' => 'api url',
            'as sms' => SmsBehavior::class,
        ],
    ],
];
```



