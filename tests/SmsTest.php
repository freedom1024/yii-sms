<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 2018/6/4
 * Time: 10:32
 */

namespace common\components;

use ryan\yii\sms\demo\DemoSms;

class SmsTest extends \PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $sms = new DemoSms();
        $response = $sms->send(13168766888, '您的验证码是123456');
        $this->assertInstanceOf('yii\httpclient\Client', $response);
    }
}