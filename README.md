============
Geocod.io PHP
============

.. image:: https://travis-ci.org/davidstanley01/geocodio-php.png?branch=master
        :target: https://travis-ci.org/davidstanley01/geocodio-php


PHP wrapper for [Geocod.io geocoding API](http://geocod.io/docs).

# Features

* Geocode an individual address
* Batch geocode up to 10,000 addresses at a time
* Parse an address into its identifiable components

Read the complete [Geocod.io geocoding API](http://geocod.io/docs) for service documentation.

# Installing via Composer

The recommended way to install Geocod.io PHP is through [Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php

# Add Geocodio as a dependency
php composer.phar require stanley\geocodio-php:~1.0
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

Using
-----

Using the Geocod.io PHP library is super simple.
```php
require_once 'vendor/autoload.php'
use Stanley\Geocodio\Client;

// Create the new Client object by passing in your api key
$client = new Client('YOUR_API_KEY');
```

## Single address
To encode a single address, use the `get` method and pass in your address as a string.
```php
$data = '123 Anywhere St, Chicago, IL';
$address = $client->get($data);
```

## Multiple addresses
For multiple addresses, call the 'post' method and pass in your data as either an object or an array.
```php
$data = [
  '123 Anywhere St, Chicago, IL',
  '456 Oak St, Los Angeles, CA'
];
$address = $client->post($data);
```

## Parsing a single address
To parse an address, call the 'parse' method and pass in your data as a string
```php
$data = '123 Anywhere St, Chicago, IL';
$address = $client->parse($data);
```

## Return Values
The Geocod.io PHP client will return an instance of the `Stanley\Geocodio\Data` class.  The raw response body can be accessed from the `response` property. This property had the response data stored as an object.

```php
$body = $address->response;
```

Soon, I'll add some helper methods so you can easily pick certain elements out.  Coming soon...

Exceptions
----------

Periodically, the Geocod.io service will return errors.

    200 OK Hopefully you will see this most of the time. Note that this status code will also be returned even though no geocoding results were available
    403 Forbidden Invalid API key or other reason why access is forbidden
    422 Unprocessable Entity A client error prevented the request from executing succesfully (e.g. invalid address provided). A JSON object will be returned with an error key containing a full error message
    500 Server Error Hopefully you will never see this...it means that something went wrong in our end. Whoops.

To handle these:

* An HTTP 403 error raises a `GeocodioAuthError`
* An HTTP 422 error raises a `GeocodioDataError` and the error message will be
  reported through the exception
* An HTTP 5xx error raises a `GeocodioServerError`
* An unmatched non-200 response will simply raise `Exception`

Credits
-------
Much of this readme and the structure of this library was inspired by the [Py-Geocodio Library](https://github.com/bennylope/pygeocodio) by [Ben Lopatin](https://github.com/bennylope).