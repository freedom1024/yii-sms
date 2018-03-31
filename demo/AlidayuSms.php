<?php
/**
 * User: Ryan
 * Date: 2017/12/06
 * Time: 15:24
 */

namespace ryan\yii\demo;

use ryan\yii\sms\BaseSms;
use yii\helpers\ArrayHelper;

/**
 * 阿里大于短信
 * Class AlidayuSms
 * @package yii\sms
 */
class AlidayuSms extends BaseSms
{
    public $appkey;
    public $secretKey;
    public $sign;
    public $templates = [];

    /**
     * @var $tel string 发送手机号
     */
    public $tel;

    /**
     * @var $content string 短信内容
     */
    public $content;

    /**
     * 阿里大于短信
     */
    const API_NAME = 'AlidayuSms';

    /**
     * @var \TopClient
     */
    private $_client;

    public function init()
    {
        parent::init();

        $this->_client = new \TopClient($this->appkey, $this->secretKey);
    }

    /**
     * 发送短信
     * @param string|array $tel 手机号码
     * @param array $content 发送内容
     * @param $ip string 客户端发送ip
     * @param $params array
     * @return mixed
     */
    public function send($tel, $content, $ip, $params = [])
    {
        $this->setTel($tel);

        /**
         * 阿里大于需要配置短信模板，$content包含短信模板id,短信变量
         * @var $templateId string 短信模板id
         * @var $params array 短信里的变量
         */
        extract($content);

        $this->trigger(self::EVENT_BEFORE_SEND, new SmsEvent([
            'params' => $params,
        ]));

        $req = new \AlibabaAliqinFcSmsNumSendRequest();
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($this->sign);
        if ($params) {
            $req->setSmsParam($this->encode($params));
        }
        //请填写需要接收的手机号码, 逗号拼接
        $req->setRecNum($this->tel);
        //短信模板id
        $req->setSmsTemplateCode($templateId);
        $resp = $this->_client->execute($req);

        $this->trigger(self::EVENT_AFTER_SEND, new SmsEvent([
            'response' => $resp
        ]));

        return $resp;
    }

    /**
     * @param $name
     * @return string
     */
    public function getTemplateId($name)
    {
        return ArrayHelper::getValue($this->templates, $name);
    }

    /**
     * @param $data
     * @return string
     */
    protected function encode($data)
    {
        $data = array_map(function ($val) {
            return (string)$val;
        }, $data);

        return json_encode($data);
    }

    /**
     * @param string|array $tel
     */
    public function setTel($tel)
    {
        $this->tel = is_array($tel) ? implode(',', $tel) : $tel;
    }
}