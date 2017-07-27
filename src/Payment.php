<?php

namespace Jetfuel\Gpp365;

use Jetfuel\Gpp365\HttpClient\CurlHttpClient;
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
        $this->apiBaseUrl = empty($apiBaseUrl) ? $this::API_BASE_URL : rtrim($apiBaseUrl, '/').'/';

        //$this->httpClient = new GuzzleHttpClient($this->apiBaseUrl);
        $this->httpClient = new CurlHttpClient($this->apiBaseUrl);
    }

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
