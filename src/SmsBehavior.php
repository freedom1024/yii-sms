<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 2017/12/7
 * Time: 17:33
 */

namespace ryan\yii\sms;


use yii\base\Behavior;

class SmsBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            BaseSms::EVENT_BEFORE_SEND => 'beforeSend',
            BaseSms::EVENT_AFTER_SEND => 'afterSend'
        ];
    }

    /**
     * @param SmsEvent $event
     */
    public function beforeSend(SmsEvent $event)
    {
        // beforeSend code goes here...

    }

    /**
     * @param SmsEvent $event
     */
    public function afterSend(SmsEvent $event)
    {
        // afterSend code goes here...
        \Yii::info($event->response, __METHOD__);

    }

}