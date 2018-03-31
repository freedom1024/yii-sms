<?php
/**
 * User: Ryan
 * Date: 2017/10/23
 * Time: 15:24
 */

namespace yii\sms;

use common\exceptions\UserException;
use common\helpers\ValidatorHelper;
use common\interfaces\UesmsInterface;
use yii\base\Component;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;

/**
 * 手机短信
 * Class Sms
 * @package yii
 */
abstract class Sms extends Component implements SmsInterface
{
    /**
     * @event ExecEvent
     */
    const EVENT_BEFORE_SEND = 'beforeSend';
    /**
     * @event ExecEvent
     */
    const EVENT_AFTER_SEND = 'afterSend';

    /**
     * 短信内容最大字数限制
     */
    const MAX_LENGTH = 201;

    /**
     * 短信计费标准
     * 70字  计费1条
     * 71字  计费2条
     * 134字 计费2条
     * 135字 计费3条
     * 201字 计费3条
     * 202字 计费4条
     */
    const SMS_FEE_START = 70;

    /**
     * 短信计费字数范围，每67字算一条短信
     */
    const SMS_FEE_RANGE = 67;

    /**
     * API name
     */
    const API_SMS = 'UeSms';
    const API_SMS_CAPTCHA = 'UeCaptchaSms';
    const API_SMS_ALIDAYU = 'AlidayuSms';

    /**
     * 短信发起的项目
     */
    const PROJECT_EC_APP = 'ec_app';
    const PROJECT_EC_PC = 'ec_pc';
    const PROJECT_EC_BACKEND = 'ec_backend';
    const PROJECT_CST_PC = 'cst_pc';
    const PROJECT_CST_XG = 'cst_xg';

    /**
     * @var string 接口url
     */
    public $url;

    /**
     * @var integer 手机号
     */
    public $tel;

    /**
     * @var string|array 短信内容
     */
    public $content;

    /**
     * @var array 手机号白名单
     */
    public static $whiteList = [
        18589031611,
        13686870771
    ];

    /**
     * @var int 同一手机号一天最多限制发送条数
     */
    public static $telLimitNum = 20;

    /**
     * @var int 同一ip一天最多限制发送条数
     */
    public static $ipLimitNum = 100;

    /**
     * 发送短信
     * @param $tel
     * @param array|string $content
     * @param $module
     * @param $ip
     * @param $apiName
     * @throws NotSupportedException
     */
    public function send($tel, $content, $module, $ip, $apiName)
    {
        throw new NotSupportedException(__METHOD__ . ' is not supported.');
    }

    /**
     * 限制同一手机号发送条数，防止盗刷短信
     * @param $tel
     * @return string
     * @throws UserException
     */
    public static function filterTel($tel)
    {
        if (!ValidatorHelper::isMobile($tel)) {
            throw new UserException('手机号码格式不正确');
        }

        $smsModel = \Yii::$app->mongodb->getCollection('smsLog');
        $dateline = strtotime(date('Y-m-d'));
        if (!ArrayHelper::isIn($tel, self::$whiteList)) {
            $telSendCount = $smsModel->count([
                'tel' => (int)$tel,
                'sendDay' => $dateline,
            ]);
            if ($telSendCount >= self::$telLimitNum) {
                throw new UserException('同一手机号发送已超过限制条数');
            }
        }

        return (string)$tel;
    }

    /**
     * 限制同一ip发送条数，防止盗刷短信
     * @param $tel
     * @param string $clientIp
     * @return string
     * @throws UserException
     */
    public static function filterIp($clientIp)
    {
        $smsModel = \Yii::$app->mongodb->getCollection('smsLog');

        if ($clientIp) {
            $dateline = strtotime(date('Y-m-d'));
            $ipSendCount = $smsModel->count([
                'ip' => $clientIp,
                'sendDay' => $dateline,
            ]);
            if ($ipSendCount >= self::$ipLimitNum) {
                throw new UserException('同一ip发送已超过限制条数');
            }
        }

        return true;
    }

    /**
     * 限制短信内容字数
     * @param $content
     * @return string
     * @throws UserException
     */
    public static function filterContent($content)
    {
        $content = trim($content);
        $length = strlen($content);
        if ($length > self::MAX_LENGTH) {
            throw new UserException('短信内容超过最大字数限制');
        }

        return $content;
    }

    /**
     * @param $project
     * @return string
     */
    public static function getModule($project)
    {
        $module = \Yii::$app->request->pathInfo;
        return "[$project][$module]";
    }

    /**
     * 计算短信计费条数
     * @param $content
     * @return float|int
     * @throws UserException
     */
    public static function calculateBillingNum($content)
    {
        $length = strlen($content);

        if ($length <= self::SMS_FEE_START) {
            $billingNum = 1;
        } else {
            $billingNum = ceil($length / self::SMS_FEE_RANGE);
        }

        return $billingNum;
    }

}