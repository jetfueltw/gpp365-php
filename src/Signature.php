<?php

namespace Jetfuel\Gpp365;

class Signature
{
    public static function generate(array $payload, $secret)
    {
        $baseString = self::buildParameterString($payload).'&key='.$secret;

        return self::md5Hash($baseString);
    }

    private static function buildParameterString(array $parameters)
    {
        ksort($parameters);

        $parameterString = '';
        foreach ($parameters as $key => $value) {
            $parameterString .= $key.'='.$value.'&';
        }

        return rtrim($parameterString, '&');
    }

    private static function md5Hash($data)
    {
        return md5($data);
    }
}
