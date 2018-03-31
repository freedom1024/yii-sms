<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 2017/12/7
 * Time: 17:33
 */

namespace yii\sms;


use yii\base\Behavior;

class SmsBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            Sms::EVENT_BEFORE_SEND => 'beforeSend',
            Sms::EVENT_AFTER_SEND => 'afterSend'
        ];
    }

    /**
     * @param SmsEvent $event
     */
    public function beforeSend(SmsEvent $event)
    {
        $event->tel = Sms::filterTel($event->tel);
        $event->content = is_array($event->content) ? $event->content : Sms::filterContent($event->content);
    }

    /**
     * @param SmsEvent $event
     */
    public function afterSend(SmsEvent $event)
    {
        \Yii::info($event->response);

        $smsLog = [
            'tel' => (int)$event->tel,
            'content' => $event->content,
            'billingNum' => is_array($event->content) ? 0 : Sms::calculateBillingNum($event->content),
            'ip' => $event->ip,
            'ret' => $event->response,
            'module' => Sms::getModule($event->module),
            'apiName' => $event->apiName,
            'date' => \Yii::$app->formatter->asDatetime(time()),
            'dateline' => time(),
            'sendDay' => strtotime(date('Y-m-d'))
        ];

        $sms = \Yii::$app->mongodb->getCollection('smsLog');
        $sms->insert($smsLog);
    }

}