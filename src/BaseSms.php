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
     * @param array $data post data
     * @param array $params event params
     * @return array|mixed
     */
    abstract public function send(array $data, array $params = []);

    /**
     * query account balance
     * @param array $data
     * @return mixed
     */
    abstract public function queryBalance(array $data);
}