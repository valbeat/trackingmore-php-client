<?php

namespace TrackingMore\TrackingMore;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private const BASE_URI = 'https://api.trackingmore.com/v4/';

    /** @var GuzzleClient */
    private $httpClient;

    public function __construct(string $apiKey, ?GuzzleClient $httpClient)
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

    public function listCarriers(): ResponseInterface
    {
        try {
            return $this->httpClient->get('carriers');
        } catch (\Exception $e) {
            throw new \RuntimeException('Error fetching carriers: ' . $e->getMessage());
        }
    }

    public function createTrackingItem(string $trackingNumber, string $carrierCode): ResponseInterface
    {
        try {
            $payload = [
                'json' => [
                    'tracking_number' => $trackingNumber,
                    'carrier_code'    => $carrierCode,
                ],
            ];

            return $this->httpClient->post('trackings', $payload);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error creating tracking item: ' . $e->getMessage());
        }
    }

    public function getTrackingItem(string $trackingNumber, string $carrierCode): ResponseInterface
    {
        try {
            return $this->httpClient->get("trackings/{$carrierCode}/{$trackingNumber}");
        } catch (\Exception $e) {
            throw new \RuntimeException('Error fetching tracking item: ' . $e->getMessage());
        }
    }

    public function updateTrackingItem(string $trackingNumber, string $carrierCode, array $updates): ResponseInterface
    {
        try {
            $payload = [
                'json' => $updates,
            ];

            return $this->httpClient->put("trackings/{$carrierCode}/{$trackingNumber}", $payload);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error updating tracking item: ' . $e->getMessage());
        }
    }

    public function deleteTrackingItem(string $trackingNumber, string $carrierCode): ResponseInterface
    {
        try {
            return $this->httpClient->delete("trackings/{$carrierCode}/{$trackingNumber}");
        } catch (\Exception $e) {
            throw new \RuntimeException('Error deleting tracking item: ' . $e->getMessage());
        }
    }

    public function batchCreateTrackingItems(array $trackingItems): ResponseInterface
    {
        try {
            $payload = [
                'json' => [
                    'trackings' => $trackingItems,
                ],
            ];

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

    public function retrackExpiredTrackingItem(string $trackingNumber, string $carrierCode): ResponseInterface
    {
        try {
            return $this->httpClient->put("trackings/{$carrierCode}/{$trackingNumber}/retrack");
        } catch (\Exception $e) {
            throw new \RuntimeException('Error retracking expired tracking item: ' . $e->getMessage());
        }
    }

    public function getRealtimeTracking(string $trackingNumber, string $carrierCode): ResponseInterface
    {
        try {
            $payload = [
                'json' => [
                    'tracking_number' => $trackingNumber,
                    'carrier_code'    => $carrierCode,
                ],
            ];

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

    public function getCarrierInfo(string $carrierCode): ResponseInterface
    {
        try {
            return $this->httpClient->get("carriers/{$carrierCode}");
        } catch (\Exception $e) {
            throw new \RuntimeException('Error fetching carrier info: ' . $e->getMessage());
        }
    }

    public function listCarrierDetect(string $trackingNumber): ResponseInterface
    {
        try {
            $payload = [
                'json' => [
                    'tracking_number' => $trackingNumber,
                ],
            ];

            return $this->httpClient->post('carriers/detect', $payload);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error detecting carrier: ' . $e->getMessage());
        }
    }
}
