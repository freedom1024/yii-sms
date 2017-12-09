<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 2017/12/7
 * Time: 17:42
 */

namespace yii\sms;


use yii\base\Event;

class SmsEvent extends Event
{
    public $tel;
    public $content = '';
    public $response;
    public $ip;
    public $module;
    public $apiName;
    public $billing;

}