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
        
        // Create a ReflectionClass to inject mock client
        $this->client = new DeepSeekClient('test-api-key');
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->client, $this->mockClient);
    }

    public function testGetSomething()
    {
        $expectedResponse = ['id' => 1, 'name' => 'test'];
        $this->mockHandler->append(
            new Response(200, [], json_encode($expectedResponse))
        );

        $response = $this->client->getSomething(1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateSomething()
    {
        $data = ['name' => 'test'];
        $expectedResponse = ['id' => 1, 'name' => 'test'];
        $this->mockHandler->append(
            new Response(201, [], json_encode($expectedResponse))
        );

        $response = $this->client->createSomething($data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateSomething()
    {
        $data = ['name' => 'updated'];
        $expectedResponse = ['id' => 1, 'name' => 'updated'];
        $this->mockHandler->append(
            new Response(200, [], json_encode($expectedResponse))
        );

        $response = $this->client->updateSomething(1, $data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteSomething()
    {
        $expectedResponse = ['status' => 'deleted'];
        $this->mockHandler->append(
            new Response(200, [], json_encode($expectedResponse))
        );

        $response = $this->client->deleteSomething(1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testRequestWithError()
    {
        $errorResponse = ['error' => 'Not found'];
        $this->mockHandler->append(
            new RequestException(
                'Not Found',
                new Request('GET', 'something/1'),
                new Response(404, [], json_encode($errorResponse))
            )
        );

        $response = $this->client->getSomething(1);
        $this->assertEquals($errorResponse, $response);
    }

    public function testRequestWithErrorNoResponse()
    {
        $this->expectException(RequestException::class);
        
        $this->mockHandler->append(
            new RequestException(
                'Network error',
                new Request('GET', 'something/1')
            )
        );

        $this->client->getSomething(1);
    }
}