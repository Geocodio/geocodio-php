geocod.io PHP
============

[![Build Status](https://travis-ci.org/Geocodio/geocodio-php.png?branch=master)](https://travis-ci.org/Geocodio/geocodio-php)

Thin PHP wrapper for [geocod.io geocoding API](http://geocod.io/docs) that includes a service provider for Laravel.

# Features

* Geocode an individual address
* Batch geocode up to 10,000 addresses at a time
* Parse an address into its identifiable components

Read the complete [geocod.io geocoding API](http://geocod.io/docs) for service documentation.

# Installing via Composer

The recommended way to install geocod.io PHP is through [Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php

# Add Geocodio as a dependency
php composer.phar require stanley/geocodio-php:~1.0
```

After installing, you need to require Composer's autoloader:

```php
require('vendor/autoload.php');
```

# Using

Using the geocod.io PHP library is super simple.
```php
require('vendor/autoload.php');
use Stanley\Geocodio\Client;

// Create the new Client object by passing in your api key
$client = new Client('YOUR_API_KEY');
```

> Note: Create an API key by signing up at https://dash.geocod.io/auth/register

## Geocode single address
To encode a single address, simply pass a string to the `geocode` function.
```php
$data = '123 Anywhere St, Chicago, IL';
$result = $client->geocode($data);
```

## Geocode multiple addresses
For multiple addresses, you can pass an array of addresses to the same `geocode` function.
```php
$data = [
  '123 Anywhere St, Chicago, IL',
  '456 Oak St, Los Angeles, CA'
];
$result = $client->geocode($data);
```

> Up to 10,000 addresses can be geocoded in a single *batch* request

## Reverse geocoding a single coordinate
geocod.io also supports reverse geocoding.  To convert a single lat/long pair into an address, call the 'reverse' method. 

The lat/long pairs should be separateed by a comma.

```php
$data = '35.9746000,-77.9658000';
$result = $client->reverse($data);
```


## Reverse geocoding multiple coordinates
To do a batch conversion, pass your lat/long pairs in an array.
```php
$data = [
    '35.9746000,-77.9658000',
    '32.8793700,-96.6303900',
    '33.8337100,-117.8362320',
    '35.4171240,-80.6784760'
];
$result = $client->reverse($data);
```

## Fields
Geocoding and reverse geocoding requests accepts an optional second parameter for [fields](https://geocod.io/docs/#fields).
```php
$data = '123 Anywhere St, Chicago, IL';
$result = $client->geocode($data, ['cd', 'stateleg']);
```

```php
$data = "35.9746000,-77.9658000";
$result = $client->reverse($data, ['cd', 'stateleg']);
```

```php
$data = [
  '123 Anywhere St, Chicago, IL',
  '456 Oak St, Los Angeles, CA'
];
$result = $client->geocode($data, ['cd', 'stateleg']);
```


## Return Values
The geocod.io PHP client will return an instance of the `Stanley\Geocodio\Data` class.  The raw response body can be accessed from the `response` property. This property had the response data stored as an object.

```php
$body = $address->response;
```

## Laravel Service Provider and Facade
A service provder and facade are available if you are using Laravel.  Once you've added the package to your composer.json file, run `php composer.phar update`. Add `'Stanley\Geocodio\ServiceProviders\LaravelServiceProvider'`to the `providers` key.  Then, edit the `aliases` key and add `'Geocodio' => 'Stanley\Geocodio\Geocodio'` to the array.

You are now ready to use the Facade.  If you are using Laravel, you will need to pass your API Key as the second parameter.
```php
$fields = [];
$key = 'YOUR_API_KEY';
$data = Geocodio::get('123 Anywhere St, Chicago, IL', $fields, $key);
return response()->json($data);
```

## Exceptions

Periodically, the geocod.io service will return [errors](https://geocod.io/docs/#errors).

To handle these:

* An HTTP 403 error raises a `GeocodioAuthError`
* An HTTP 422 error raises a `GeocodioDataError` and the error message will be
  reported through the exception
* An HTTP 5xx error raises a `GeocodioServerError`
* An unmatched non-200 response will simply raise `Exception`

## Credits
The original library was written by [David Stanley](https://github.com/davidstanley01). Much of this readme and the structure of this library was inspired by the [Py-Geocodio Library](https://github.com/bennylope/pygeocodio) by [Ben Lopatin](https://github.com/bennylope).
