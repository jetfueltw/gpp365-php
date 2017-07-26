<?php

namespace Jetfuel\Gpp365\HttpClient;

interface HttpClientInterface
{
    public function __construct($baseUrl);

    public function post($uri, array $data);
}
