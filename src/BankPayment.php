<?php

namespace Jetfuel\Gpp365;


class BankPayment
{
    public function __construct($appId, $appSecret, $apiBaseUrl = null)
    {
        parent::__construct($appId, $appSecret, $apiBaseUrl);
    }

    //public function bank($userId, $tradeNo, $amount, $deviceType, $ip, $returnUrl, $notifyUrl, $bankSwiftCode)
    //{
    //    $payload = [
    //        'merchant'      => $this->appId,
    //        'tradeNo'       => $tradeNo,
    //        'bankSwiftCode' => $bankSwiftCode,
    //        'amount'        => $amount,
    //        'curType'       => 'CNY',
    //        'userId'        => $userId,
    //        'deviceType'    => $deviceType,
    //        'ip'            => $ip,
    //        'returnUrl'     => $returnUrl,
    //        'notifyUrl'     => $notifyUrl,
    //        'reqTime'       => $this->getShanghaiCurrentTime(),
    //    ];
    //
    //    $signature = Signature::generate($payload, $this->appSecret);
    //    $payload['sign'] = $signature;
    //
    //    return $this->httpClient->post('pay/bank/v1', $payload);
    //}
}
