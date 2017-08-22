<?php

use Jetfuel\Gpp365\Constants\Bank;
use Jetfuel\Gpp365\Constants\Device;
use Jetfuel\Gpp365\Constants\Provider;
use Jetfuel\Gpp365\BankPayment;
use Jetfuel\Gpp365\DigitalPayment;
use Jetfuel\Gpp365\Signature;
use Symfony\Component\DomCrawler\Crawler;

class Test extends PHPUnit_Framework_TestCase
{
    private $appId     = '1496219556263';
    private $appSecret = 'UigNOCfqB6Kt';
    private $channel   = Provider::WECHAT;
    private $bank      = Bank::ABOC;
    private $device    = Device::WEB;

    public function testDigitalPaymentOrder()
    {
        $tradeNo = (string)time();
        $payment = new DigitalPayment($this->appId, $this->appSecret);

        $result = $payment->order($tradeNo, $this->channel, 100, 1, $this->device, '127.0.0.1', 'http://www.wechat.com');

        $this->assertEquals('0000', $result['code']);

        return $tradeNo;
    }

    /**
     * @depends testDigitalPaymentOrder
     */
    public function testDigitalPaymentCheck($tradeNo)
    {
        $payment = new DigitalPayment($this->appId, $this->appSecret);

        $result = $payment->check($tradeNo, $this->channel);

        $this->assertEquals('0000', $result['code']);
    }

    public function testBankPaymentOrder()
    {
        $tradeNo = (string)time();
        $payment = new BankPayment($this->appId, $this->appSecret);

        $result = $payment->order($tradeNo, $this->bank, 100, 1, $this->device, '127.0.0.1', 'http://www.wechat.com');

        $this->assertContains('name="tradeNo"', $result);

        // 第一次跳轉
        $crawler = new Crawler($result);

        $url = $crawler->filter('form')->attr('action');
        $payload = [
            'PMerchantId'   => $crawler->filter('input[name="PMerchantId"]')->attr('value'),
            'PThirdPartyId' => $crawler->filter('input[name="PThirdPartyId"]')->attr('value'),
            'amount'        => $crawler->filter('input[name="amount"]')->attr('value'),
            'bankSwiftCode' => $crawler->filter('input[name="bankSwiftCode"]')->attr('value'),
            'curType'       => $crawler->filter('input[name="curType"]')->attr('value'),
            'deviceType'    => $crawler->filter('input[name="deviceType"]')->attr('value'),
            'ip'            => $crawler->filter('input[name="ip"]')->attr('value'),
            'merchant'      => $crawler->filter('input[name="merchant"]')->attr('value'),
            'notifyUrl'     => $crawler->filter('input[name="notifyUrl"]')->attr('value'),
            'reqTime'       => $crawler->filter('input[name="reqTime"]')->attr('value'),
            'sign'          => $crawler->filter('input[name="sign"]')->attr('value'),
            'tradeNo'       => $crawler->filter('input[name="tradeNo"]')->attr('value'),
            'userId'        => $crawler->filter('input[name="userId"]')->attr('value'),
        ];

        $client = new \GuzzleHttp\Client();

        $response = $client->post($url, [
            'form_params' => $payload,
        ]);
        $result = $response->getBody()->getContents();

        $this->assertContains('window.location = ', $result);

        // 第二次跳轉
        $crawler = new Crawler($result);

        $url = $crawler->filter('script[type="text/javascript"]')->text();
        $url = trim(str_replace('window.location = ', '', $url), '"');

        $response = $client->get($url);

        $this->assertEquals(200, $response->getStatusCode());

        return $payload['tradeNo'];
    }

    /**
     * @depends testBankPaymentOrder
     */
    public function testBankPaymentCheck($tradeNo)
    {
        $payment = new BankPayment($this->appId, $this->appSecret);

        $result = $payment->check($tradeNo);

        $this->assertEquals('0000', $result['code']);
    }

    public function testSignatureGenerate()
    {
        $payload = [
            'tradeNo'    => '1501137459',
            'payType'    => Provider::WECHAT,
            'amount'     => 100,
            'curType'    => 'CNY',
            'userId'     => 1,
            'deviceType' => Device::WEB,
            'ip'         => '127.0.0.1',
            'notifyUrl'  => 'http://www.wechat.com',
            'merchant'   => '1496219556263',
            'reqTime'    => '2017-07-27 14:37:39',
        ];

        $signature = Signature::generate($payload, 'UigNOCfqB6Kt');

        $this->assertEquals($signature, 'febbd71ef138bd6b86044e5b8324a105');
    }

    public function testSignatureValidate()
    {
        $payload = [
            'tradeNo'     => '1501145550',
            'payType'     => Provider::WECHAT,
            'amount'      => '100.00',
            'curType'     => 'CNY',
            'merchant'    => '1496219556263',
            'ordernumber' => '10150114555125622432',
            'qrcode'      => 'weixin://wxpay/bizpayurl?pr=8YkhfW3',
            'sign'        => '56b7557d9e895a43019c64358f5d0249',
        ];

        $isValid = Signature::validate($payload, 'UigNOCfqB6Kt');

        $this->assertTrue($isValid);
    }
}
