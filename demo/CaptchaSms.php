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
class CaptchaSms extends BaseSms
{
    /**
     * @var $action string 发送任务命令
     */
    public $action;

    /**
     * @var $userid integer 企业id
     */
    public $userid;

    /**
     * @var $account string 发送用户帐号
     */
    public $account;

    /**
     * @var $userid string 发送用户密码
     */
    public $password;

    /**
     * @var $extno string 扩展子号
     */
    public $extno;

    /**
     * @var $sign string 短信签名
     */
    public $sign;

    /**
     * @var $tel string 发送手机号
     */
    public $tel;

    /**
     * @var $content string 短信内容
     */
    public $content;

    /**
     * 优易网短信验证码
     */
    const API_NAME = 'UeSms';

    /**
     * 短信后缀
     */
    const SMS_SUFFIX = ' 退订回T';

    /**
     * 发送短信
     * @param string|array $tel 手机号码
     * @param string $content 发送内容
     * @param $ip string 客户端发送ip
     * @param $params array
     * @return mixed
     */
    public function send($tel, $content, $ip, $params = [])
    {
        $this->setTel($tel);
        $this->setContent($content);
        $this->setData();

        $response = parent::send($tel, $content, $ip, $params);

        return $response;
    }

    /**
     * 账户余额查询
     * @return array|mixed
     */
    public function queryBalance()
    {
        $httpClient = \Yii::$app->http;

        $this->action = 'overage';
        $this->setData();
        $request = $httpClient->post($this->url, $this->data);
        $response = $httpClient->send($request);
        $response = $response->getData();

        return $response;
    }

    /**
     * set post data
     */
    public function setData()
    {
        $this->data = [
            'mobile' => $this->tel,
            'content' => $this->content,
            'action' => $this->action,
            'account' => $this->account,
            'password' => $this->password,
            'userid' => $this->userid,
            'extno' => $this->extno
        ];
    }

    /**
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = "【{$this->sign}】" . trim($content) . self::SMS_SUFFIX;
    }

    /**
     * @param string|array $tel
     */
    public function setTel($tel)
    {
        $this->tel = is_array($tel) ? implode(',', $tel) : $tel;
    }
}