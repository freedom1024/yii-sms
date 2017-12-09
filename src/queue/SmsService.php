<?php
/**
 * User: Ryan
 * Date: 2017/5/4
 * Time: 14:17
 */

namespace common\services;

use common\components\queue\SendSms;

/**
 * 短信服务.
 * @package common\services
 */
class SmsService
{
    /**
     * 通过子账号发送短信
     * @param $shopId
     * @param $tel
     * @param array|string $content
     * @param $module
     * @param string $apiName
     * @param string $ip
     */
    public function sendSubSms($shopId, $tel, $content, $module, $ip)
    {
        \Yii::$app->queue->push(new SendSubSms([
            'shopId' => $shopId,
            'tel' => $tel,
            'content' => $content,
            'module' => $module,
            'ip' => $ip,
        ]));
    }


}