History
=======

1.1.0 (2014-02-03)
------------------
* Added reverse geocoding endpoint.

1.0.6 (2014-01-26)
------------------
* Added integration tests and fixed issue where post data was being encoded twice.

1.0.5 (2014-01-23)
------------------
* Specified minimum version of Guzzle at >=3.7.0

1.0.4 (2014-01-22)
------------------
* Added service provider and facade for Laravel
* Added method to auto-detect endpoint required for single or multiple address (not for `parse` endpoint)
* Refactored some methods to allow for API Key to be set after instantiation via setter or optional parameter.
* Added GeocodioException instead of using general exception

1.0.3 (2014-01-22)
------------------
* Forgot to bump version in composer.json

1.0.2 (2014-01-22)
------------------
* Fixed mis-named class property

1.0.1 (2014-01-22)
------------------
* Added license and changelog

1.0.0 (2014-01-22)
------------------

* Initial release to Packagist