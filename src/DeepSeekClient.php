<?php

namespace DeepSeek;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DeepSeekClient
{
    private Client $client;
    private string $apiKey;
    private array $messages = [];

    public function __construct($apiKey)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.deepseek.com/',
            'timeout'  => 30.0,
        ]);

        $this->apiKey = $apiKey;
    }

    private function request($method, $uri, $options = [])
    {
        $options['headers'] = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json'
        ];

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

    public function setMessage(string $role, string $content): self
    {
        $this->messages[] = [
            'role' => $role,
            'content' => $content
        ];
        return $this;
    }

    public function clearMessages(): self
    {
        $this->messages = [];
        return $this;
    }

    public function send(): array
    {
        $payload = [
            'model' => 'deepseek-chat',
            'messages' => $this->messages,
            'stream' => false
        ];

        return $this->request('POST', 'chat/completions', [
            'json' => $payload
        ]);
    }
}