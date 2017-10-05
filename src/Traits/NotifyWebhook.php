<?php

namespace Jetfuel\Gpp365\Traits;

use Jetfuel\Gpp365\Signature;

trait NotifyWebhook
{
    /**
     * Verify notify request's signature.
     *
     * @param array $payload
     * @param string $secretKey
     * @return bool
     */
    public function verifyNotifyPayload(array $payload, $secretKey)
    {
        if (!isset($payload['sign'])) {
            return false;
        }

        $signature = $payload['sign'];

        unset($payload['sign']);

        return Signature::validate($payload, $secretKey, $signature);
    }

    /**
     * Response content for successful notify.
     *
     * @return string
     */
    public function successNotifyResponse()
    {
        return 'SUCCESS';
    }
}
