<?php

namespace Jetfuel\Gpp365;

use Jetfuel\Gpp365\HttpClient\GuzzleHttpClient;

class Payment
{
    const API_BASE_URL = 'https://test-apiproxy.gpp365.net/';

    /**
     * @var string
     */
    protected $appId;

    /**
     * @var string
     */
    protected $appSecret;

    /**
     * @var string
     */
    protected $apiBaseUrl;

    /**
     * @var \Jetfuel\Gpp365\HttpClient\HttpClientInterface
     */
    protected $httpClient;

    protected function __construct($appId, $appSecret, $apiBaseUrl)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->apiBaseUrl = empty($apiBaseUrl) ? $this::API_BASE_URL : $apiBaseUrl;

        $this->httpClient = new GuzzleHttpClient($this->apiBaseUrl);
    }

    //public function query($tradeNo, $payType)
    //{
    //    $payload = [
    //        'merchant' => $this->appId,
    //        'tradeNo'  => $tradeNo,
    //        'payType'  => $payType,
    //        'reqTime'  => $this->getShanghaiCurrentTime(),
    //    ];
    //
    //    $signature = Signature::generate($payload, $this->appSecret);
    //    $payload['sign'] = $signature;
    //
    //    return $this->httpClient->post('query/v1', $payload);
    //}

    protected function signPayload($payload)
    {
        $payload['merchant'] = $this->appId;
        $payload['reqTime'] = $this->getShanghaiCurrentTime();
        $payload['sign'] = Signature::generate($payload, $this->appSecret);

        return $payload;
    }

    protected function getShanghaiCurrentTime()
    {
        return (new \DateTime('now', new \DateTimeZone('Asia/Shanghai')))->format('Y-m-d H:i:s');
    }
}
