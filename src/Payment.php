<?php

namespace Jetfuel\Gpp365;

use Jetfuel\Gpp365\HttpClient\GuzzleHttpClient;

class Payment
{
    const TIME_ZONE   = 'Asia/Shanghai';

    /**
     * @var string
     */
    protected $merchantId;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $baseApiUrl;

    /**
     * @var \Jetfuel\Gpp365\HttpClient\HttpClientInterface
     */
    protected $httpClient;

    /**
     * Payment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param string $baseApiUrl
     */
    protected function __construct($merchantId, $secretKey, $baseApiUrl)
    {
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;
        $this->baseApiUrl = $baseApiUrl;

        $this->httpClient = new GuzzleHttpClient($this->baseApiUrl);
    }

    /**
     * Sign request payload.
     *
     * @param $payload
     * @return array
     */
    protected function signPayload($payload)
    {
        $payload['merchant'] = $this->merchantId;
        $payload['reqTime'] = $this->getCurrentTime();
        $payload['sign'] = Signature::generate($payload, $this->secretKey);

        return $payload;
    }

    /**
     * Get current time.
     *
     * @return string
     */
    protected function getCurrentTime()
    {
        return (new \DateTime('now', new \DateTimeZone(self::TIME_ZONE)))->format('Y-m-d H:i:s');
    }
}
