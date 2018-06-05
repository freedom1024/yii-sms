<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 2018/4/17
 * Time: 20:36
 */

namespace ryan\yii\sms;


use yii\base\Component;

abstract class BaseSms extends Component
{
    /**
     * send sms
     * @param array|string $data post data
     * @param array $params event params
     * @param array $header curl headers
     * @return array|mixed
     * @throws \yii\httpclient\Exception
     */
    abstract public function send($data, array $params = [], $headers = []);

    /**
     * query account balance
     * @param $data
     * @return mixed
     */
    abstract public function queryBalance($data);
	
    /**
     * get sms statusReport
     * @param $data
     * @return mixed
     */
    abstract public function getStatusReport($data);
}