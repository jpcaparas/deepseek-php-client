<?php

namespace DeepSeek\Tests;

use DeepSeek\DeepSeekClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class DeepSeekClientTest extends TestCase
{
    private $mockClient;
    private $mockHandler;
    private $client;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $this->mockClient = new Client(['handler' => $handlerStack]);
        
        $this->client = new DeepSeekClient('test-api-key');
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->client, $this->mockClient);
    }

    public function testSetMessage()
    {
        $this->client->setMessage('user', 'Hello');
        
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('messages');
        $property->setAccessible(true);
        
        $messages = $property->getValue($this->client);
        $this->assertEquals([
            ['role' => 'user', 'content' => 'Hello']
        ], $messages);
    }

    public function testClearMessages()
    {
        $this->client->setMessage('user', 'Hello');
        $this->client->clearMessages();
        
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('messages');
        $property->setAccessible(true);
        
        $messages = $property->getValue($this->client);
        $this->assertEquals([], $messages);
    }

    public function testSend()
    {
        $expectedResponse = [
            'choices' => [
                ['message' => ['content' => 'Hello there!']]
            ]
        ];
        
        $this->mockHandler->append(
            new Response(200, [], json_encode($expectedResponse))
        );

        $this->client->setMessage('user', 'Hello');
        $response = $this->client->send();
        
        $this->assertEquals($expectedResponse, $response);
    }

    public function testSendWithError()
    {
        $errorResponse = ['error' => 'Invalid request'];
        $this->mockHandler->append(
            new RequestException(
                'Bad Request',
                new Request('POST', 'chat/completions'),
                new Response(400, [], json_encode($errorResponse))
            )
        );

        $this->client->setMessage('user', 'Hello');
        $response = $this->client->send();
        
        $this->assertEquals($errorResponse, $response);
    }
}