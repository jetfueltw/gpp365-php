<?php

namespace Jetfuel\Gpp365;

class TradeQuery extends Payment
{
    const BASE_API_URL = 'https://test-apiproxy.gpp365.net/';

    /**
     * DigitalPayment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantId, $secretKey, $baseApiUrl = null)
    {
        $baseApiUrl = $baseApiUrl === null ? self::BASE_API_URL : $baseApiUrl;

        parent::__construct($merchantId, $secretKey, $baseApiUrl);
    }

    /**
     * @param string $tradeNo
     * @param int $channel
     * @return array
     */
    public function find($tradeNo, $channel = 3)
    {
        $payload = $this->signPayload([
            'tradeNo' => $tradeNo,
            'payType' => $channel,
        ]);

        return json_decode($this->httpClient->post('query/v1', $payload), true);
    }
}
