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
     * @event SmsEvent
     */
    const EVENT_BEFORE_SEND = 'beforeSend';

    /**
     * @event SmsEvent
     */
    const EVENT_AFTER_SEND = 'afterSend';

    /**
     * @var $url string api url
     */
    public $url;

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
     * @param array $header curl headers
     * @return mixed
     */
    abstract public function queryBalance($data, $headers = []);
}