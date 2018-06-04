<?php
/**
 * User: Ryan
 * Date: 2017/10/23
 * Time: 15:24
 */

namespace ryan\yii\sms\demo;

use ryan\yii\sms\Sms;
use ryan\yii\sms\SmsBehavior;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * Class DemoSms
 * @package yii\sms;
 */
class DemoSms extends Component
{
    /**
     * @var string api url
     */
    public $url;

    /**
     * @var array api config
     */
    public $config;

    /**
     * @var string 短信签名
     */
    public $sign;

    /**
     * @var string 短信后缀
     */
    public $suffix;

    /**
     * 发送短信
     * @param string|array $tel 手机号码
     * @param string $content 发送内容
     * @param $params array event params
     * @return mixed
     */
    public function send($tel, $content, $params = [])
    {
        $tel = $this->handleTel($tel);
        $content = $this->handleContent($content);

        /* @var $sms Sms */
        $sms = \Yii::createObject(Sms::class, ['url' => $this->url]);
        $sms->attachBehavior('sms', SmsBehavior::class);
        $response = $sms->send(ArrayHelper::merge($this->config, [
            'mobile' => $tel,
            'content' => $content
        ]), $params);

        return $response;
    }

    /**
     * 账户余额查询
     * @return array|mixed
     */
    public function queryBalance()
    {
        /* @var $sms Sms */
        $sms = \Yii::createObject(Sms::class, ['url' => $this->url]);
        $response = $sms->queryBalance(ArrayHelper::merge($this->config, [
            'action' => 'overage'
        ]));

        return $response;
    }

    /**
     * @param $content
     * @return string
     */
    public function handleContent($content)
    {
        return "【{$this->sign}】" . trim($content) . $this->suffix;
    }

    /**
     * @param $tel
     * @return string
     */
    public function handleTel($tel)
    {
        return is_array($tel) ? implode(',', $tel) : $tel;
    }
}