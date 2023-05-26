# TrackingMore PHP Client

[![Test](https://github.com/valbeat/trackingmore-php-client/actions/workflows/test.yml/badge.svg?branch=main)](https://github.com/valbeat/trackingmore-php-client/actions/workflows/test.yml) [![Latest Stable Version](http://poser.pugx.org/valbeat/trackingmore-php-client/v)](https://packagist.org/packages/valbeat/trackingmore-php-client) [![License](http://poser.pugx.org/valbeat/trackingmore-php-client/license)](https://packagist.org/packages/valbeat/trackingmore-php-client) [![PHP Version Require](http://poser.pugx.org/valbeat/trackingmore-php-client/require/php)](https://packagist.org/packages/valbeat/trackingmore-php-client)

A PHP client library for the TrackingMore API.

## Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require valbeat/trackingmore-php-client
```

## Requirements

PHP 7.2 or above for the latest version.

## Usage

```php
<?php

use TrackingMore\Client;

$apiKey = 'your-api-key';
$client = new Client($apiKey);

// Create a tracking
$trackingData = [...];
$response = $client->createTracking($trackingData);

// Get tracking details
$trackingNumber = 'tracking-number';
$trackingDetails = $client->getTracking($trackingNumber);

// Update tracking details
$updateData = [...];
$response = $client->updateTracking($trackingNumber, $updateData);

// Delete tracking
$response = $client->deleteTracking($trackingNumber);

// And more...
```

## API Documentation

For more information about the TrackingMore API, visit the [official documentation](https://www.trackingmore.com/docs/trackingmore/e3f9re5cu7ude-api-overview).


## License

This project is licensed under the MIT License.
