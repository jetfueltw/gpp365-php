<?php

namespace Jetfuel\Gpp365;

use Jetfuel\Gpp365\Constants\Device;
use Jetfuel\Gpp365\Traits\ResultParser;

class DigitalPayment extends Payment
{
    use ResultParser;

    const CUR_TYPE    = 'CNY';
    const USER_ID     = '1';
    const DEVICE_TYPE = Device::WEB;

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
     * @param string $tradeNo
     * @param int $channel
     * @param float $amount
     * @param string $clientIp
     * @param string $notifyUrl
     * @return array
     */
    public function order($tradeNo, $channel, $amount, $clientIp, $notifyUrl)
    {
        $payload = $this->signPayload([
            'tradeNo'    => $tradeNo,
            'payType'    => $channel,
            'amount'     => $amount,
            'curType'    => self::CUR_TYPE,
            'userId'     => self::USER_ID,
            'deviceType' => self::DEVICE_TYPE,
            'ip'         => $clientIp,
            'notifyUrl'  => $notifyUrl,
        ]);

        return $this->parseResponse($this->httpClient->post('pay/qrcode/v1', $payload));
    }
}
