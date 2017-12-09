<?php
/**
 * User: Ryan
 * Date: 2017/5/4
 * Time: 14:17
 */

namespace yii\sms;

use yii\sms\queue\SendSms;

/**
 * 短信队列服务.
 * @package common\services
 */
class SmsService
{
    /**
     * @param $shopId
     * @param $tel
     * @param array|string $content
     * @param $module
     * @param string $apiName
     * @param string $ip
     */
    public function sendSms($tel, $content, $module, $ip)
    {
        \Yii::$app->queue->push(new SendSms([
            'tel' => $tel,
            'content' => $content,
            'module' => $module,
            'ip' => $ip,
        ]));
    }


}