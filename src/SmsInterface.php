<?php
/**
 * User: Ryan
 * Date: 2017/10/23
 * Time: 15:24
 */

namespace ryan\yii\sms;

interface SmsInterface
{
    /**
     * 发送短信
     * @param string|array $tel 手机号码
     * @param string|array $content 发送内容
     * @param $ip string 客户端发送ip
     * @param $params array
     * @return mixed
     */
    public function send($tel, $content, $ip, $params = []);
}