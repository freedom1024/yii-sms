<?php
/**
 * User: Ryan
 * Date: 2017/10/23
 * Time: 15:24
 */

namespace yii\sms;

use common\exceptions\UserException;
use common\models\center\Area;
use yii\helpers\ArrayHelper;

/**
 * 优易网普通短信
 * Class Uesms
 * @package yii
 */
class UeSms extends Sms
{
    /**
     * @var $_sms UeSms
     */
    private static $_sms;

    /**
     * @var integer 4s店id
     */
    public $shopId;

    /**
     * @var string 接口url
     */
    public $url = 'http://www.ueswt4.com/sms.aspx';

    /**
     * 短信配置信息
     */
    public $action;
    public $userid;
    public $account;
    public $password;
    public $extno;
    public $sign;

    /**
     * 获取短信子账号实例
     * @param $shopId
     * @return UeSms
     * @throws UserException
     */
    public static function getSmsInstance($shopId)
    {
        $shopModel = Area::find()
            ->joinWith(['extend'])
            ->areaId($shopId)
            ->active()
            ->one();

        if (!$shopModel) {
            throw new UserException('4s店不存在！');
        }

        $userid    = ArrayHelper::getValue($shopModel, 'extend.sms_userid');
        $account   = ArrayHelper::getValue($shopModel, 'extend.sms_account');
        $password  = ArrayHelper::getValue($shopModel, 'extend.sms_password');
        $signature = ArrayHelper::getValue($shopModel, 'extend.sms_signature');

        if (!$userid || !$account || !$password || !$signature) {
            throw new UserException('短信账号配置错误！');
        }

        if (!self::$_sms) {
            self::$_sms = \Yii::createObject([
                'class' => UeSms::class,
                'action' => 'send',
                'extno' => '',
                'userid' => trim($userid),
                'account' => trim($account),
                'password' => trim($password),
                'sign' => trim($signature),
                'areaId' => $shopId,
                'as sms' => SmsBehavior::class,
            ]);
        }

        return self::$_sms;
    }

    /**
     * 通过子账号发送短信
     * @param $tel
     * @param string $content
     * @param $module
     * @param string $apiName
     * @param string $ip
     * @return bool
     */
    public function send($tel, $content, $module, $ip, $apiName = self::API_SMS)
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
     * 账户余额查询
     * @return array|mixed
     */
    public function queryBalance()
    {
        $httpClient = \Yii::$app->http;

        $request = $httpClient->post($this->url, [
            'userid' => $this->userid,
            'account' => $this->account,
            'password' => $this->password,
            'action' => 'overage'
        ]);
        $response = $httpClient->send($request);
        $response = $response->getData();

        if (ArrayHelper::getValue($response, 'returnstatus') == 'Sucess') {
            return (int)ArrayHelper::getValue($response, 'overage');
        } else {
            return 0;
        }
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
            'userid' => $this->userid,
            'extno' => $this->extno
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