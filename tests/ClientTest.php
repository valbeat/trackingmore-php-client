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

    public function testListCouriers(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"couriers": []}'));

        $response = $this->client->listCouriers();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"couriers": []}', (string)$response->getBody());
    }

    public function testCreateTracking(): void
    {
        $trackingNumber = '1234567890';
        $courierCode    = 'test-courier';

        $this->mockHandler->append(new Response(200, [], '{"tracking_number": "1234567890", "courier_code": "test-courier"}'));

        $response = $this->client->createTracking($trackingNumber, $courierCode);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"tracking_number": "1234567890", "courier_code": "test-courier"}', (string)$response->getBody());
    }

    public function testUpdateTracking(): void
    {
        $trackingNumber = '1234567890';
        $courierCode    = 'test-courier';
        $title          = 'Updated title';

        $this->mockHandler->append(new Response(200, [], '{"tracking_number": "1234567890", "courier_code": "test-courier", "title": "Updated title"}'));

        $response = $this->client->updateTracking($trackingNumber, $courierCode, ['title' => $title]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"tracking_number": "1234567890", "courier_code": "test-courier", "title": "Updated title"}', (string)$response->getBody());
    }

    public function testDeleteTracking(): void
    {
        $trackingNumber = '1234567890';
        $courierCode    = 'test-courier';
        $this->mockHandler->append(new Response(200, [], '{"tracking_number": "1234567890", "courier_code": "test-courier", "status": "deleted"}'));

        $response = $this->client->deleteTracking($trackingNumber, $courierCode);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"tracking_number": "1234567890", "courier_code": "test-courier", "status": "deleted"}', (string)$response->getBody());
    }

    public function testGetTrackings(): void
    {
        $this->mockHandler->append(new Response(200, [], '{"s": [{"tracking_number": "1234567890", "courier_code": "test-courier"}]}'));

        $response = $this->client->getTrackings();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"s": [{"tracking_number": "1234567890", "courier_code": "test-courier"}]}', (string)$response->getBody());
    }

    public function testCreateTrackings(): void
    {
        $trackings = [
            [
                'tracking_number' => '1234567890',
                'courier_code'    => 'test-courier',
            ],
            [
                'tracking_number' => '0987654321',
                'courier_code'    => 'test-courier',
            ],
        ];

        $this->mockHandler->append(new Response(200, [], '{"s": [{"tracking_number": "1234567890", "courier_code": "test-courier"}, {"tracking_number": "0987654321", "courier_code": "test-courier"}]}'));

        $response = $this->client->createTrackings($trackings);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"s": [{"tracking_number": "1234567890", "courier_code": "test-courier"}, {"tracking_number": "0987654321", "courier_code": "test-courier"}]}', (string)$response->getBody());
    }

    public function testRetrackExpiredTracking(): void
    {
        $trackingNumber = '1234567890';

        $this->mockHandler->append(new Response(200, [], '{"tracking_number": "1234567890", "courier_code": "test-courier", "status": "retracked"}'));

        $response = $this->client->retrackExpiredTracking($trackingNumber);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"tracking_number": "1234567890", "courier_code": "test-courier", "status": "retracked"}', (string)$response->getBody());
    }
}
