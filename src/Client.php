<?php

namespace TrackingMore;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
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
            return $this->httpClient->get('couriers/all');
        } catch (\Exception $e) {
            throw new \RuntimeException('Error fetching couriers: ' . $e->getMessage());
        }
    }

    public function detectCouriers(string $trackingNumber): ResponseInterface
    {
        $payload = [
            RequestOptions::JSON => [
                'tracking_number' => $trackingNumber,
            ],
        ];

        try {
            return $this->httpClient->post('couriers/detect', $payload);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error detecting courier: ' . $e->getMessage());
        }
    }

    public function createTracking(array $tracking): ResponseInterface
    {
        $payload = [
            RequestOptions::JSON => $tracking,
        ];

        try {
            return $this->httpClient->post('trackings', $payload);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error creating tracking: ' . $e->getMessage());
        }
    }

    public function updateTracking(string $trackingNumber,array $updates): ResponseInterface
    {
        try {
            $payload = [
                RequestOptions::JSON => $updates,
            ];

            return $this->httpClient->put("trackings/{$trackingNumber}", $payload);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error updating tracking: ' . $e->getMessage());
        }
    }

    public function deleteTracking(string $trackingNumber): ResponseInterface
    {
        try {
            return $this->httpClient->delete("trackings/{$trackingNumber}");
        } catch (\Exception $e) {
            throw new \RuntimeException('Error deleting tracking : ' . $e->getMessage());
        }
    }

    public function createTrackings(array $trackings): ResponseInterface
    {
        $payload = [
            RequestOptions::JSON => [
                'trackings' => $trackings,
            ],
        ];

        try {
            return $this->httpClient->post('trackings/batch', $payload);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error batch creating trackings: ' . $e->getMessage());
        }
    }

    public function getTrackings(array $parameters = []): ResponseInterface
    {
        try {
            return $this->httpClient->get(
                'trackings',
                [
                    RequestOptions::QUERY => $parameters,
                ]
            );
        } catch (\Exception $e) {
            throw new \RuntimeException('Error fetching trackings: ' . $e->getMessage());
        }
    }

    public function retrackExpiredTracking(string $trackingNumber): ResponseInterface
    {
        try {
            return $this->httpClient->post("trackings/retrack/{$trackingNumber}");
        } catch (\Exception $e) {
            throw new \RuntimeException('Error retracking expired tracking: ' . $e->getMessage());
        }
    }
}
