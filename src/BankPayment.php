<?php

namespace Jetfuel\Gpp365;

class BankPayment extends Payment
{
    public function __construct($appId, $appSecret, $baseApiUrl = null)
    {
        parent::__construct($appId, $appSecret, $baseApiUrl);
    }

    /**
     * @param string $tradeNo
     * @param int $bank
     * @param float $amount
     * @param int $userId
     * @param int $device
     * @param string $ip
     * @param string $notifyUrl
     * @return string
     */
    public function order($tradeNo, $bank, $amount, $userId, $device, $ip, $notifyUrl)
    {
        $payload = $this->signPayload([
            'tradeNo'       => $tradeNo,
            'bankSwiftCode' => $bank,
            'amount'        => $amount,
            'curType'       => 'CNY',
            'userId'        => $userId,
            'deviceType'    => $device,
            'ip'            => $ip,
            'notifyUrl'     => $notifyUrl,
        ]);

        return $this->httpClient->post('pay/bank/v1', $payload);
    }

    /**
     * @param string $tradeNo
     * @return array
     */
    public function check($tradeNo)
    {
        $payload = $this->signPayload([
            'tradeNo' => $tradeNo,
            'payType' => 3,
        ]);

        return json_decode($this->httpClient->post('query/v1', $payload), true);
    }
}
