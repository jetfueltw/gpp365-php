<?php

namespace Test;

use Faker\Factory;
use Jetfuel\Gpp365\BankPayment;
use Jetfuel\Gpp365\Constants\Bank;
use Jetfuel\Gpp365\Constants\Channel;
use Jetfuel\Gpp365\DigitalPayment;
use Jetfuel\Gpp365\TradeQuery;
use Jetfuel\Gpp365\Traits\NotifyWebhook;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    private $merchantId;
    private $secretKey;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->merchantId = getenv('MERCHANT_ID');
        $this->secretKey = getenv('SECRET_KEY');
    }

    public function testDigitalPaymentOrder()
    {
        $faker = Factory::create();
        $tradeNo = $faker->uuid;
        $channel = Channel::WECHAT;
        $amount = 1;
        $clientIp = $faker->ipv4;
        $notifyUrl = $faker->url;

        $payment = new DigitalPayment($this->merchantId, $this->secretKey);
        $result = $payment->order($tradeNo, $channel, $amount, $clientIp, $notifyUrl);

        $this->assertEquals('0000', $result['code']);

        return $tradeNo;
    }

    /**
     * @depends testDigitalPaymentOrder
     */
    public function testDigitalPaymentOrderFind($tradeNo)
    {
        $channel = Channel::WECHAT;

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
        $result = $tradeQuery->find($tradeNo, $channel);

        $this->assertEquals('0000', $result['code']);
    }

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    public function testDigitalPaymentOrderIsPaid($tradeNo)
    {
        $channel = Channel::WECHAT;

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
        $result = $tradeQuery->isPaid($tradeNo, $channel);

        $this->assertFalse($result);
    }

    public function testBankPaymentOrder()
    {
        $faker = Factory::create();
        $tradeNo = $faker->uuid;
        $bank = Bank::ICBK;
        $amount = 1;
        $clientIp = $faker->ipv4;
        $notifyUrl = $faker->url;

        $payment = new BankPayment($this->merchantId, $this->secretKey);
        $result = $payment->order($tradeNo, $bank, $amount, $clientIp, $notifyUrl);

        $this->assertContains($tradeNo, $result);

        return $tradeNo;
    }

    /**
     * @depends testBankPaymentOrder
     *
     * @param $tradeNo
     */
    public function testBankPaymentOrderFind($tradeNo)
    {
        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
        $result = $tradeQuery->find($tradeNo);

        $this->assertEquals('00', $result['code']);
    }

    /**
     * @depends testBankPaymentOrder
     *
     * @param $tradeNo
     */
    public function testBankPaymentOrderIsPaid($tradeNo)
    {
        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
        $result = $tradeQuery->isPaid($tradeNo);

        $this->assertFalse($result);
    }

    public function testTradeQueryFindOrderNotExist()
    {
        $faker = Factory::create();
        $tradeNo = $faker->uuid;

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
        $result = $tradeQuery->find($tradeNo);

        $this->assertNull($result);
    }

    public function testTradeQueryIsPaidOrderNotExist()
    {
        $faker = Factory::create();
        $tradeNo = $faker->uuid;

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
        $result = $tradeQuery->isPaid($tradeNo);

        $this->assertFalse($result);
    }

    public function testNotifyWebhookVerifyNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'merchant'    => '1234567',
            'ordernumber' => '10149379108515420868',
            'tradeNo'     => '12345671234567',
            'payType'     => '3',
            'amount'      => '1.00',
            'curType'     => 'CNY',
            'status'      => '0',
            'tradeTime'   => '2017-01-01 00:00:00',
            'sign'        => 'fbf2b52fd5aec45f2cf84198cd750144',
        ];

        $this->assertTrue($mock->verifyNotifyPayload($payload, 'UigNOCfqB6Kt'));
    }

    public function testNotifyWebhookParseNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'merchant'    => '1234567',
            'ordernumber' => '10149379108515420868',
            'tradeNo'     => '12345671234567',
            'payType'     => '3',
            'amount'      => '1.00',
            'curType'     => 'CNY',
            'status'      => '0',
            'tradeTime'   => '2017-01-01 00:00:00',
            'sign'        => 'fbf2b52fd5aec45f2cf84198cd750144',
        ];

        $this->assertEquals($payload, $mock->parseNotifyPayload($payload, 'UigNOCfqB6Kt'));
    }

    public function testNotifyWebhookSuccessNotifyResponse()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $this->assertEquals('SUCCESS', $mock->successNotifyResponse());
    }
}
