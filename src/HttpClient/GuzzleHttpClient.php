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

        //var_dump($response->getStatusCode());
        //var_dump($response->getHeaders());
        //var_dump($response->getBody());

        return json_decode($response->getBody(), true);
    }
}
