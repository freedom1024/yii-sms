<?php
/**
 * User: Ryan
 * Date: 2017/10/23
 * Time: 15:24
 */

namespace yii\sms;
use yii\helpers\ArrayHelper;

/**
 * 优易网短信验证码（通过总账号发送）
 * Class UeCaptchaSms
 * @package yii
 */
class UeCaptchaSms extends Sms
{
    /**
     * 短信配置信息
     */
    public $action;
    public $account;
    public $password;
    public $extno;
    public $sign;

    /**
     * 通过总账号发送短信
     * @param $tel
     * @param string $content
     * @param $module
     * @param string $apiName
     * @param string $ip
     * @return bool
     */
    public function send($tel, $content, $module, $ip, $apiName = self::API_SMS_CAPTCHA)
    {
        $event = new SmsEvent([
            'tel' => $tel,
            'content' => $this->setContent($content),
            'module' => $module,
            'ip' => $ip,
            'apiName' => $apiName,
        ]);
        $this->trigger(self::EVENT_BEFORE_SEND, $event);

        $httpClient = \Yii::$app->http;
        $request = $httpClient->post($this->url, $this->bindParams($event->tel, $event->content));
        $response = $httpClient->send($request);
        $response = $response->getData();

        $event->response = $response;
        $this->trigger(self::EVENT_AFTER_SEND, $event);

        return $response;
    }

    /**
     * 绑定参数
     * @param $tel
     * @param $content
     * @return array
     */
    public function bindParams($tel, $content)
    {
        return [
            'mobile' => $tel,
            'content' => $content,
            'action' => $this->action,
            'account' => $this->account,
            'password' => $this->password,
            'extno' => $this->extno,
        ];
    }

    /**
     * @param $content
     * @return string
     */
    public function setContent($content)
    {
        return "【{$this->sign}】" . trim($content);
    }
}