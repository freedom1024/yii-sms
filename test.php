<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 2017/12/9
 * Time: 15:15
 */

//子账号发送短信
\Yii::$app->subSms->getSmsInstance($shopId)->send(
    13168766000,
    '周末愉快',
    Sms::PROJECT_CST_PC,
    \Yii::$app->request->userIP
);

//总账号发送短信
\Yii::$app->generalSms->send(
    13168766000,
    '验证码是123，退订回T',
    Sms::PROJECT_CST_PC,
    \Yii::$app->request->userIP
);

//阿里大于发送短信
\Yii::createObject([
    'class' => AlidayuSms::class,
    'appkey' => '23461692',
    'secretKey' => '1065a59a8c26a7bbc5a3dad73633426f',
    'sign' => '车商通',
    'as sms' => SmsBehavior::class,
])->send(
    [13168766000],
    [
        'templateId' => 'SMS_63320454',
        'params' => [
            'account' => 13165766333,
            'password' => 123,
        ]
    ],
    Sms::PROJECT_CST_PC,
    \Yii::$app->request->userIP
);