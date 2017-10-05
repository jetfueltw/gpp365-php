<?php

use Jetfuel\Gpp365\Constants\Bank;
use Jetfuel\Gpp365\Constants\Channel;
use Jetfuel\Gpp365\BankPayment;
use Jetfuel\Gpp365\DigitalPayment;
use Jetfuel\Gpp365\TradeQuery;
use Jetfuel\Gpp365\Traits\NotifyWebhook;

class Test extends PHPUnit_Framework_TestCase
{
    use NotifyWebhook;

    private $merchantId = '1496219556263';
    private $secretKey  = 'UigNOCfqB6Kt';
    private $channel    = Channel::WECHAT;
    private $bank       = Bank::ABOC;

    public function testDigitalPaymentOrder()
    {
        $tradeNo = (string)time();
        $payment = new DigitalPayment($this->merchantId, $this->secretKey);

        $result = $payment->order($tradeNo, $this->channel, 1, '127.0.0.1', 'https://www.tencent.com');

        $this->assertEquals('0000', $result['code']);

        return $tradeNo;
    }

    /**
     * @depends testDigitalPaymentOrder
     */
    public function testDigitalPaymentCheck($tradeNo)
    {
        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);

        $result = $tradeQuery->find($tradeNo, $this->channel);

        $this->assertEquals('0000', $result['code']);
    }

    public function testBankPaymentOrder()
    {
        $tradeNo = (string)time();
        $payment = new BankPayment($this->merchantId, $this->secretKey);

        $result = $payment->order($tradeNo, $this->bank, 1, '127.0.0.1', 'https://www.tencent.com');

        $this->assertContains($tradeNo, $result);

        return $tradeNo;
    }
}
