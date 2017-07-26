<?php

namespace Jetfuel\Gpp365;

class DigitalPayment extends Payment
{
    public function __construct($appId, $appSecret, $apiBaseUrl = null)
    {
        parent::__construct($appId, $appSecret, $apiBaseUrl);
    }

    public function order($tradeNo, $provider, $amount, $userId, $device, $ip, $notifyUrl)
    {
        $payload = $this->signPayload([
            'tradeNo'    => $tradeNo,
            'payType'    => $provider,
            'amount'     => $amount,
            'curType'    => 'CNY',
            'userId'     => $userId,
            'deviceType' => $device,
            'ip'         => $ip,
            'notifyUrl'  => $notifyUrl,
        ]);

        return $this->httpClient->post('pay/qrcode/v1', $payload);
    }

    public function check($tradeNo, $provider)
    {
        $payload = $this->signPayload([
            'tradeNo' => $tradeNo,
            'payType' => $provider,
        ]);

        return $this->httpClient->post('query/v1', $payload);
    }
}
