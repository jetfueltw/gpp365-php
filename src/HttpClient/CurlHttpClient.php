<?php

namespace Jetfuel\Gpp365\HttpClient;

class CurlHttpClient implements HttpClientInterface
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var resource
     */
    private $client;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->client = curl_init();
    }

    public function post($uri, array $data)
    {
        $dataString = json_encode($data);

        curl_setopt_array($this->client, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_URL            => $this->baseUrl.$uri,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Content-Length: '.strlen($dataString),
            ],
            CURLOPT_POSTFIELDS     => $dataString,
        ]);

        $result = curl_exec($this->client);

        return $result;
    }

    public function __destruct()
    {
        curl_close($this->client);
    }
}
