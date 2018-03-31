<?php
/**
 * User: Ryan
 * Date: 2017/11/17
 * Time: 9:18
 */

namespace yii\sms\queue;

use yii\base\Object;

/**
 * 发送短信队列
 * Class SendSms
 * @package yii\sms\queue
 */
class SendSms extends Object implements \yii\queue\Job
{
    /**
     * 手机号
     * @var string
     */
    public $tel;

    /**
     * 内容
     * @var array|string
     */
    public $content;

    /**
     * 发送短信的模块
     * @var string
     */
    public $module;

    /**
     * 客户端ip
     * @var string
     */
    public $ip;

    /**
     * @param \yii\queue\Queue $queue
     */
    public function execute($queue)
    {
        \Yii::$app->sms->send(
            $this->tel,
            $this->content,
            $this->module,
            $this->ip
        );
    }
}