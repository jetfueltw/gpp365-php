<?php

namespace Jetfuel\Gpp365\HttpClient;

use GuzzleHttp\Client;

class GuzzleHttpClient implements HttpClientInterface
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * GuzzleHttpClient constructor.
     *
     * @param string $baseUrl
     */
    public function __construct($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/').'/';
        $this->client = new Client();
    }

    /**
     * POST request.
     *
     * @param string $uri
     * @param array $data
     * @return string
     */
    public function post($uri, array $data)
    {
        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
        ];
        $response = $this->client->post($this->baseUrl.$uri, [
            'headers' => $headers,
            'json'    => $data,
        ]);

        return $response->getBody()->getContents();
    }
}
