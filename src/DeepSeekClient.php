<?php

namespace DeepSeek;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DeepSeekClient
{
    private Client $client;
    private string $apiKey;

    public function __construct($apiKey)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.deepseek.com/v1/',
            'timeout'  => 2.0,
        ]);
        $this->apiKey = $apiKey;
    }

    private function request($method, $uri, $options = [])
    {
        $options['headers']['Authorization'] = 'Bearer ' . $this->apiKey;

        try {
            $response = $this->client->request($method, $uri, $options);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return json_decode($e->getResponse()->getBody(), true);
            }
            throw $e;
        }
    }

    public function getSomething($id)
    {
        return $this->request('GET', "something/{$id}");
    }

    public function createSomething($data)
    {
        return $this->request('POST', 'something', ['json' => $data]);
    }

    public function updateSomething($id, $data)
    {
        return $this->request('PUT', "something/{$id}", ['json' => $data]);
    }

    public function deleteSomething($id)
    {
        return $this->request('DELETE', "something/{$id}");
    }
}
