<?php

namespace Jetfuel\Gpp365\HttpClient;

use GuzzleHttp\Client;

class GuzzleHttpClient implements HttpClientInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    public function __construct($baseUrl)
    {
        $this->client = new Client([
            'base_uri' => $baseUrl,
        ]);
    }

    public function post($uri, array $data)
    {
        $response = $this->client->post($uri, [
            'json' => $data,
        ]);

        return $response->getBody()->getContents();
    }
}
