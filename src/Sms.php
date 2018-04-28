<?php
/**
 * User: Ryan
 * Date: 2017/10/23
 * Time: 15:24
 */

namespace ryan\yii\sms;

/**
 * Yii2 short message
 * Class Sms
 * @package common\components
 */
class Sms extends BaseSms implements SmsInterface
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
     * @param array $data post data
     * @param array $params event params
     * @return array|mixed
     */
    public function send(array $data, array $params = [])
    {
        $this->trigger(self::EVENT_BEFORE_SEND, new SmsEvent([
            'params' => $params,
        ]));

        $httpClient = \Yii::$app->http;
        $request = $httpClient->post($this->url, $data);
        $send = $httpClient->send($request);
        $response = $send->getData();

        $this->trigger(self::EVENT_AFTER_SEND, new SmsEvent([
            'response' => $response
        ]));

        return $response;
    }

    /**
     * query account balance
     * @param array $data
     * @return mixed
     */
    public function queryBalance(array $data)
    {
        $httpClient = \Yii::$app->http;

        $request = $httpClient->post($this->url, $data);
        $response = $httpClient->send($request);
        $response = $response->getData();

        return $response;
    }
}