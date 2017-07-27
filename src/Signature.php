<?php

namespace Jetfuel\Gpp365;

class Signature
{
    /**
     * @param array $payload
     * @param string $secret
     * @return string
     */
    public static function generate(array $payload, $secret)
    {
        $baseString = self::buildParameterString($payload).'key='.$secret;

        return self::md5Hash($baseString);
    }

    /**
     * @param array $payload
     * @param string $secret
     * @return bool
     */
    public static function validate(array $payload, $secret)
    {
        if (!isset($payload['sign'])) {
            return false;
        }

        $signature = $payload['sign'];
        unset($payload['sign']);

        $baseString = self::buildParameterString($payload).'key='.$secret;

        return self::md5Hash($baseString) === $signature;
    }

    private static function buildParameterString(array $parameters)
    {
        ksort($parameters);

        $parameterString = '';
        foreach ($parameters as $key => $value) {
            $parameterString .= $key.'='.$value.'&';
        }

        return $parameterString;
    }

    private static function md5Hash($data)
    {
        return md5($data);
    }
}
