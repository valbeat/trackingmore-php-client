# TrackingMore PHP Client

A PHP client library for the TrackingMore API.

## Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require valbeat/trackingmore-php-client
```

## Usage

```php
<?php

use TrackingMore\TrackingMore\Client;

$apiKey = 'your-api-key';
$client = new Client($apiKey);

// Create a tracking
$trackingData = [...];
$response = $client->createTracking($trackingData);

// Get tracking details
$slug = 'carrier-slug';
$trackingNumber = 'tracking-number';
$trackingDetails = $client->getTracking($slug, $trackingNumber);

// Update tracking details
$updateData = [...];
$response = $client->updateTracking($slug, $trackingNumber, $updateData);

// Delete tracking
$response = $client->deleteTracking($slug, $trackingNumber);

// And more...
```

## API Documentation

For more information about the TrackingMore API, visit the [official documentation](https://www.trackingmore.com/docs/trackingmore/e3f9re5cu7ude-api-overview).


## License

This project is licensed under the MIT License.
