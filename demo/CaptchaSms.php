<?php
/**
 * User: Ryan
 * Date: 2017/10/23
 * Time: 15:24
 */

namespace ryan\yii\demo;

use ryan\yii\sms\BaseSms;

/**
 * captcha sms
 * Class CaptchaSms
 * @package yii\sms;
 */
class CaptchaSms
{
    /**
     * @var USERID integer 企业id
     */
    const USERID = '6666';

    /**
     * @var ACCOUNT string 发送用户帐号
     */
    const ACCOUNT = '6666';

    /**
     * @var PASSWORD string 发送用户密码
     */
    const PASSWORD = '6666';

    /**
     * @var EXTNO string 扩展子号
     */
    const EXTNO = '6666';

    /**
     * @var SIGN string 短信签名
     */
    const SIGN = 'your sign';

    /**
     * @var $tel string 发送手机号
     */
    public $tel;

    /**
     * @var $content string 短信内容
     */
    public $content;

    /**
     * 短信后缀
     */
    const SMS_SUFFIX = ' 退订回T';

    /**
     * 发送短信
     * @param string|array $tel 手机号码
     * @param string $content 发送内容
     * @param $params array extra params
     * @return mixed
     */
    public function send($tel, $content, $params = [])
    {
        $this->setTel($tel);
        $this->setContent($content);

        $response = \Yii::$app->sms->send(array_merge($this->config, [
            'tel' => $this->tel,
            'content' => $this->content,
            'action' => 'send'
        ]), $params);

        return $response;
    }

    /**
     * 账户余额查询
     * @return array|mixed
     */
    public function queryBalance()
    {
        $response = \Yii::$app->sms->queryBalance(array_merge($this->config, [
            'action' => 'overage'
        ]));

        return $response;
    }

    /**
     * get config
     */
    public function getConfig()
    {
        return [
            'account' => self::ACCOUNT,
            'password' => self::PASSWORD,
            'userid' => self::USERID,
            'extno' => self::EXTNO
        ];
    }

    /**
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = "【".self::SIGN."】" . trim($content) . self::SMS_SUFFIX;
    }

    /**
     * @param string|array $tel
     */
    public function setTel($tel)
    {
        $this->tel = is_array($tel) ? implode(',', $tel) : $tel;
    }
}