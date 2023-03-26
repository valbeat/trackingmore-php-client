<?php

namespace TrackingMore;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private const BASE_URI = 'https://api.trackingmore.com/v4/';

    /** @var GuzzleClient */
    private $httpClient;

    public function __construct(string $apiKey, ?GuzzleClient $httpClient = null)
    {
        if ($httpClient) {
            $this->httpClient = $httpClient;
            return;
        }
        $this->httpClient = new GuzzleClient(
            [
                'base_uri' => self::BASE_URI,
                'headers'  => [
                    'Content-Type'     => 'application/json',
                    'Tracking-Api-Key' => $apiKey,
                ],
            ]
        );
    }

    public function listCouriers(): ResponseInterface
    {
        try {
            return $this->httpClient->get('couriers');
        } catch (\Exception $e) {
            throw new \RuntimeException('Error fetching couriers: ' . $e->getMessage());
        }
    }

    public function createTrackingItem(string $trackingNumber, string $courierCode): ResponseInterface
    {
        $payload = [
            'json' => [
                'tracking_number' => $trackingNumber,
                'courier_code'    => $courierCode,
            ],
        ];

        try {
            return $this->httpClient->post('trackings', $payload);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error creating tracking item: ' . $e->getMessage());
        }
    }

    public function getTrackingItem(string $trackingNumbers): ResponseInterface
    {
        $params = [
            'query' => [
                'tracking_numbers' => $trackingNumbers,
            ],
        ];
        try {

            return $this->httpClient->get("trackings/get", $params);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error fetching tracking item: ' . $e->getMessage());
        }
    }

    public function updateTrackingItem(string $trackingNumber, string $courierCode, array $updates): ResponseInterface
    {
        try {
            $payload = [
                'json' => $updates,
            ];

            return $this->httpClient->put("trackings/{$courierCode}/{$trackingNumber}", $payload);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error updating tracking item: ' . $e->getMessage());
        }
    }

    public function deleteTrackingItem(string $trackingNumber, string $courierCode): ResponseInterface
    {
        try {
            return $this->httpClient->delete("trackings/{$courierCode}/{$trackingNumber}");
        } catch (\Exception $e) {
            throw new \RuntimeException('Error deleting tracking item: ' . $e->getMessage());
        }
    }

    public function batchCreateTrackingItems(array $trackingItems): ResponseInterface
    {
        $payload = [
            'json' => [
                'trackings' => $trackingItems,
            ],
        ];

        try {
            return $this->httpClient->post('trackings/batch', $payload);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error batch creating tracking items: ' . $e->getMessage());
        }
    }

    public function getTrackingItems(array $parameters = []): ResponseInterface
    {
        try {
            return $this->httpClient->get('trackings', ['query' => $parameters]);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error fetching tracking items: ' . $e->getMessage());
        }
    }

    public function retrackExpiredTrackingItem(string $trackingNumber): ResponseInterface
    {
        try {
            return $this->httpClient->post("trackings/retrack/{$trackingNumber}");
        } catch (\Exception $e) {
            throw new \RuntimeException('Error retracking expired tracking item: ' . $e->getMessage());
        }
    }

    public function getRealtimeTracking(string $trackingNumber, string $courierCode): ResponseInterface
    {
        $payload = [
            'json' => [
                'tracking_number' => $trackingNumber,
                'courier_code'    => $courierCode,
            ],
        ];

        try {
            return $this->httpClient->post('trackings/realtime', $payload);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error fetching realtime tracking: ' . $e->getMessage());
        }
    }

    public function getUserInfo(): ResponseInterface
    {
        try {
            return $this->httpClient->get('user/info');
        } catch (\Exception $e) {
            throw new \RuntimeException('Error fetching user info: ' . $e->getMessage());
        }
    }

    public function getCourierInfo(string $courierCode): ResponseInterface
    {
        try {
            return $this->httpClient->get("couriers/{$courierCode}");
        } catch (\Exception $e) {
            throw new \RuntimeException('Error fetching courier info: ' . $e->getMessage());
        }
    }

    public function listCourierDetect(string $trackingNumber): ResponseInterface
    {
        $payload = [
            'json' => [
                'tracking_number' => $trackingNumber,
            ],
        ];

        try {
            return $this->httpClient->post('couriers/detect', $payload);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error detecting courier: ' . $e->getMessage());
        }
    }
}
