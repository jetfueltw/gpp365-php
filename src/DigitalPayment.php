<?php

namespace Jetfuel\Gpp365;

class DigitalPayment extends Payment
{
    public function __construct($appId, $appSecret, $apiBaseUrl = null)
    {
        parent::__construct($appId, $appSecret, $apiBaseUrl);
    }

    /**
     * @param string $tradeNo
     * @param int $channel
     * @param float $amount
     * @param int $userId
     * @param int $device
     * @param string $ip
     * @param string $notifyUrl
     * @return array
     */
    public function order($tradeNo, $channel, $amount, $userId, $device, $ip, $notifyUrl)
    {
        $payload = $this->signPayload([
            'tradeNo'    => $tradeNo,
            'payType'    => $channel,
            'amount'     => $amount,
            'curType'    => 'CNY',
            'userId'     => $userId,
            'deviceType' => $device,
            'ip'         => $ip,
            'notifyUrl'  => $notifyUrl,
        ]);

        return json_decode($this->httpClient->post('pay/qrcode/v1', $payload), true);
    }

    /**
     * @param string $tradeNo
     * @param int $provider
     * @return array
     */
    public function check($tradeNo, $provider)
    {
        $payload = $this->signPayload([
            'tradeNo' => $tradeNo,
            'payType' => $provider,
        ]);

        return json_decode($this->httpClient->post('query/v1', $payload), true);
    }
}
