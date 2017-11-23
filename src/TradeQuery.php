<?php

namespace Jetfuel\Gpp365;

use Jetfuel\Gpp365\Traits\ResultParser;

class TradeQuery extends Payment
{
    use ResultParser;

    /**
     * DigitalPayment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantId, $secretKey, $baseApiUrl = null)
    {
        parent::__construct($merchantId, $secretKey, $baseApiUrl);
    }

    /**
     * Find Order by trade number.
     *
     * @param string $tradeNo
     * @param int $channel
     * @return array|null
     */
    public function find($tradeNo, $channel = 3)
    {
        $payload = $this->signPayload([
            'tradeNo' => $tradeNo,
            'payType' => $channel,
        ]);

        $order = $this->parseResponse($this->httpClient->post('query/v1', $payload));

        if ($order['code'] !== '0000') {
            return null;
        }

        return $order;
    }

    /**
     * Is order already paid.
     *
     * @param string $tradeNo
     * @param int $channel
     * @return bool
     */
    public function isPaid($tradeNo, $channel = 3)
    {
        $order = $this->find($tradeNo, $channel);

        if ($order === null || !isset($order['data']['status']) || $order['data']['status'] !== '1') {
            return false;
        }

        return true;
    }
}
