<?php


use Jetfuel\Gpp365\Constants\Bank;
use Jetfuel\Gpp365\Constants\Device;
use Jetfuel\Gpp365\Constants\Provider;
use Jetfuel\Gpp365\DigitalPayment;

class Test extends PHPUnit_Framework_TestCase
{
    private $appId = '1496219556263';
    private $appSecret = 'UigNOCfqB6Kt';
    private $provider = Provider::WECHAT;
    private $device = Device::WEB;

    public function testDigitalPaymentOrder()
    {
        $tradeNo = (string)time();

        $payment = new DigitalPayment($this->appId, $this->appSecret);

        $result = $payment->order($tradeNo, $this->provider, 100, 1, $this->device, '127.0.0.1', 'http://www.wechat.com');

        $this->assertEquals('0000', $result['code']);

        return $tradeNo;
    }

    /**
     * @depends testDigitalPaymentOrder
     */
    public function testDigitalPaymentCheck($tradeNo)
    {
        $payment = new DigitalPayment($this->appId, $this->appSecret);

        $result = $payment->check($tradeNo, $this->provider);

        $this->assertEquals('0000', $result['code']);
    }

    //public function testBank()
    //{
    //    $payment = new Payment($this->appId, $this->appSecret);
    //
    //    $userId = 1;
    //    $tradeNo = (string)(time() + 1);
    //    $amount = 100;
    //    $deviceType = Device::WEB;
    //    $ip = '218.161.61.999';
    //    $returnUrl = 'http://www.wechat.com';
    //    $notifyUrl = 'http://www.wechat.com';
    //    $bankSwiftCode = Bank::ABOC;
    //
    //    $result = $payment->bank($userId, $tradeNo, $amount, $deviceType, $ip, $returnUrl, $notifyUrl, $bankSwiftCode);
    //
    //    var_dump($result);
    //}

}
