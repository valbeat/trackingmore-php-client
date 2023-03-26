<?php

namespace TrackingMore\Test;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TrackingMore\Client;

class ClientTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var MockHandler
     */
    private $mockHandler;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack      = HandlerStack::create($this->mockHandler);

        $guzzleClient = new GuzzleClient([
                                             'handler' => $handlerStack,
                                         ]);

        $apiKey       = 'your-api-key';
        $this->client = new Client($apiKey, $guzzleClient);
    }

    public function testListCarriers(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"carriers": []}'));

        $response = $this->client->listCarriers();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"carriers": []}', (string)$response->getBody());
    }

    public function testCreateTrackingItem(): void
    {
        $trackingNumber = '1234567890';
        $carrierCode    = 'test-carrier';

        $this->mockHandler->append(new Response(200, [], '{"tracking_number": "1234567890", "carrier_code": "test-carrier"}'));

        $response = $this->client->createTrackingItem($trackingNumber, $carrierCode);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"tracking_number": "1234567890", "carrier_code": "test-carrier"}', (string)$response->getBody());
    }

    public function testGetTrackingItem(): void
    {
        $trackingNumber = '1234567890';
        $carrierCode    = 'test-carrier';

        $this->mockHandler->append(new Response(200, [], '{"tracking_number": "1234567890", "carrier_code": "test-carrier"}'));

        $response = $this->client->getTrackingItem($trackingNumber, $carrierCode);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"tracking_number": "1234567890", "carrier_code": "test-carrier"}', (string)$response->getBody());
    }

    public function testUpdateTrackingItem(): void
    {
        $trackingNumber = '1234567890';
        $carrierCode    = 'test-carrier';
        $title          = 'Updated title';

        $this->mockHandler->append(new Response(200, [], '{"tracking_number": "1234567890", "carrier_code": "test-carrier", "title": "Updated title"}'));

        $response = $this->client->updateTrackingItem($trackingNumber, $carrierCode, ['title' => $title]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"tracking_number": "1234567890", "carrier_code": "test-carrier", "title": "Updated title"}', (string)$response->getBody());
    }

    public function testDeleteTrackingItem(): void
    {
        $trackingNumber = '1234567890';
        $carrierCode    = 'test-carrier';
        $this->mockHandler->append(new Response(200, [], '{"tracking_number": "1234567890", "carrier_code": "test-carrier", "status": "deleted"}'));

        $response = $this->client->deleteTrackingItem($trackingNumber, $carrierCode);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"tracking_number": "1234567890", "carrier_code": "test-carrier", "status": "deleted"}', (string)$response->getBody());
    }

    public function testGetTrackingItems(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"items": [{"tracking_number": "1234567890", "carrier_code": "test-carrier"}]}'));

        $response = $this->client->getTrackingItems();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"items": [{"tracking_number": "1234567890", "carrier_code": "test-carrier"}]}', (string)$response->getBody());
    }

    public function testBatchCreateTrackingItems(): void
    {
        $trackingItems = [
            [
                'tracking_number' => '1234567890',
                'carrier_code'    => 'test-carrier',
            ],
            [
                'tracking_number' => '0987654321',
                'carrier_code'    => 'test-carrier',
            ],
        ];

        $this->mockHandler->append(new Response(200, [], '{"items": [{"tracking_number": "1234567890", "carrier_code": "test-carrier"}, {"tracking_number": "0987654321", "carrier_code": "test-carrier"}]}'));

        $response = $this->client->batchCreateTrackingItems($trackingItems);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"items": [{"tracking_number": "1234567890", "carrier_code": "test-carrier"}, {"tracking_number": "0987654321", "carrier_code": "test-carrier"}]}', (string)$response->getBody());
    }

    public function testRetrackExpiredTrackingItem(): void
    {
        $trackingNumber = '1234567890';

        $this->mockHandler->append(new Response(200, [], '{"tracking_number": "1234567890", "carrier_code": "test-carrier", "status": "retracked"}'));

        $response = $this->client->retrackExpiredTrackingItem($trackingNumber);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"tracking_number": "1234567890", "carrier_code": "test-carrier", "status": "retracked"}', (string)$response->getBody());
    }
}
