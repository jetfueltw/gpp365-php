<?php

namespace Jetfuel\Gpp365\HttpClient;

interface HttpClientInterface
{
    /**
     * HttpClientInterface constructor.
     *
     * @param string $baseUrl
     */
    public function __construct($baseUrl);

    /**
     * POST request.
     *
     * @param string $uri
     * @param array $data
     * @return string
     */
    public function post($uri, array $data);
}
