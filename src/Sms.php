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
     * @param array|string $data post data
     * @param array $params event params
     * @param array $header curl headers
     * @return array|mixed
     * @throws \yii\httpclient\Exception
     */
    public function send($data, array $params = [], $headers = [])
    {
        $this->trigger(self::EVENT_BEFORE_SEND, new SmsEvent([
            'params' => $params,
        ]));

        $httpClient = \Yii::$app->http;
        $request = $httpClient->post($this->url, $data, $headers);
        $send = $httpClient->send($request);
        $response = $send->getData();

        $this->trigger(self::EVENT_AFTER_SEND, new SmsEvent([
            'response' => $response
        ]));

        return $response;
    }

    /**
     * query account balance
     * @param $data
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
	
    /**
     * get sms statusReport
     * @param $data
     * @return mixed
     */
    public function getStatusReport(array $data)
    {
        $httpClient = \Yii::$app->http;

        $request = $httpClient->post($this->url, $data);
        $response = $httpClient->send($request);
        $response = $response->getData();

        return $response;
    }	
}