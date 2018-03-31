<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 2017/12/7
 * Time: 17:42
 */

namespace ryan\yii\sms;


use yii\base\Event;

class SmsEvent extends Event
{
    /**
     * @var $params
     */
    public $params;

    /**
     * @var $response
     */
    public $response;
}