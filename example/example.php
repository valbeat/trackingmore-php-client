<?php

require_once __DIR__ . '/../vendor/autoload.php';

use TrackingMore\Client;

// Please set your api key
$apiKey = getenv('TRACKINGMORE_API_KEY');

$client = new Client($apiKey);

// Get all carriers
$response = $client->listCouriers();
echo "List couriers :\n";
echo $response->getBody();

// Get tracking
$response = $client->getTrackings(['tracking_numbers' => 12345678910]);
echo "Trackings :\n";
echo $response->getBody();

// Retrack expired tracking
$response = $client->retrackExpiredTracking('abcd1234abcd1234abcd1234');
echo "Retrack :\n";
echo $response->getBody();
