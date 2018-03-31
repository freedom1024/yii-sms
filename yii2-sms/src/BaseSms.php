<?php
/**
 * User: Ryan
 * Date: 2017/10/23
 * Time: 15:24
 */

namespace ryan\yii\sms;

use yii\base\Component;

/**
 * 手机短信
 * Class BaseSms
 * @package common\components
 */
abstract class BaseSms extends Component implements SmsInterface
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
     * @var $url string 接口url
     */
    public $url;

    /**
     * @var $data array postData
     */
    public $data;

    /**
     * 发送短信
     * @param string|array $tel 手机号码
     * @param string|array $content 发送内容
     * @param $ip string 客户端发送ip
     * @param $params array
     * @return array|mixed
     */
    public function send($tel, $content, $ip, $params = [])
    {
        $this->trigger(self::EVENT_BEFORE_SEND, new SmsEvent([
            'params' => $params,
        ]));

        $httpClient = \Yii::$app->http;
        $request = $httpClient->post($this->url, $this->data);
        $send = $httpClient->send($request);
        $response = $send->getData();

        $this->trigger(self::EVENT_AFTER_SEND, new SmsEvent([
            'response' => $response
        ]));

        return $response;
    }

}