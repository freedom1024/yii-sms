<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 2017/12/7
 * Time: 17:42
 */

namespace ryan\yii\sms;

interface SmsInterface
{
    /**
     * send sms
     * @param array|string $data post data
     * @param array $params event params
     * @param array $header curl headers
     * @return array|mixed
     * @throws \yii\httpclient\Exception
     */
    public function send($data, array $params = [], $headers = []);
}