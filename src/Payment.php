<?php

namespace Jetfuel\Gpp365;

use Jetfuel\Gpp365\HttpClient\GuzzleHttpClient;

class Payment
{
    const BASE_API_URL = 'https://test-apiproxy.gpp365.net/';
    const SERVICE_TIME_ZONE = 'Asia/Shanghai';

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
    protected $baseApiUrl;

    /**
     * @var \Jetfuel\Gpp365\HttpClient\HttpClientInterface
     */
    protected $httpClient;

    protected function __construct($appId, $appSecret, $baseApiUrl)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->baseApiUrl = empty($baseApiUrl) ? $this::BASE_API_URL : rtrim($baseApiUrl, '/').'/';

        $this->httpClient = new GuzzleHttpClient($this->baseApiUrl);
    }

    protected function signPayload($payload)
    {
        $payload['merchant'] = $this->appId;
        $payload['reqTime'] = $this->getCurrentTime();
        $payload['sign'] = Signature::generate($payload, $this->appSecret);

        return $payload;
    }

    protected function getCurrentTime()
    {
        return (new \DateTime('now', new \DateTimeZone(self::SERVICE_TIME_ZONE)))->format('Y-m-d H:i:s');
    }
}
