<?php
/**
 * User: Ryan
 * Date: 2017/12/06
 * Time: 15:24
 */

namespace yii\sms;
use yii\helpers\ArrayHelper;

/**
 * 阿里大于短信
 * Class AlidayuSms
 * @package yii
 */
class AlidayuSms extends Sms
{
    public $appkey;
    public $secretKey;
    public $sign;
    public $templates = [];

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
     * @param $tels
     * @param array $content
     * @param $ip
     * @param string $module
     * @param string $apiName
     * @return bool
     */
    public function send($tels, $content, $module, $ip, $apiName = self::API_SMS_ALIDAYU)
    {
        /**
         * @var $templateId string 短信模板id
         * @var $params string 短信内容
         */
        extract($content);
        $tel = reset($tels);

        $event = new SmsEvent([
            'tel' => $tel,
            'content' => $content,
            'module' => $module,
            'ip' => $ip,
            'apiName' => $apiName,
        ]);
        $this->trigger(self::EVENT_BEFORE_SEND, $event);

        $req = new \AlibabaAliqinFcSmsNumSendRequest();
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($this->sign);
        if ($params) {
            $req->setSmsParam($this->encode($params));
        }
        //请填写需要接收的手机号码, 逗号拼接
        $req->setRecNum($event->tel);
        //短信模板id
        $req->setSmsTemplateCode($templateId);
        $resp = $this->_client->execute($req);

        $event->response = $resp;
        $this->trigger(self::EVENT_AFTER_SEND, $event);

        if (ArrayHelper::getValue($resp, 'result.success')) {
            return true;
        } else {
            return false;
        }
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
}