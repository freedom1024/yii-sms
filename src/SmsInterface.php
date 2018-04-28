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
     * @param array $data post data
     * @param array $params event params
     * @return array|mixed
     */
    public function send(array $data, array $params = []);
}