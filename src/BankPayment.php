<?php

namespace Jetfuel\Gpp365;

use Jetfuel\Gpp365\Constants\Device;

class BankPayment extends Payment
{
    const CUR_TYPE    = 'CNY';
    const USER_ID     = '1';
    const DEVICE_TYPE = Device::WEB;

    /**
     * BankPayment constructor.
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
     * @param string $tradeNo
     * @param int $bank
     * @param float $amount
     * @param string $clientIp
     * @param string $notifyUrl
     * @return string
     */
    public function order($tradeNo, $bank, $amount, $clientIp, $notifyUrl)
    {
        $payload = $this->signPayload([
            'tradeNo'       => $tradeNo,
            'bankSwiftCode' => $bank,
            'amount'        => $amount,
            'curType'       => self::CUR_TYPE,
            'userId'        => self::USER_ID,
            'deviceType'    => self::DEVICE_TYPE,
            'ip'            => $clientIp,
            'notifyUrl'     => $notifyUrl,
        ]);

        return $this->httpClient->post('pay/bank/v1', $payload);
    }
}
