[![Build Status](https://travis-ci.org/hades200082/SecureSoapClient.png)](https://travis-ci.org/hades200082/SecureSoapClient)

SecureSoapClient
================

The SecureSoapClient class is an extension of the native PHP SoapClient that uses cURL to make requests.

This ensures that any requests over **https** are actually secure by default.

The class otherwise acts in exactly the same way as the native SoapClient.

Usage
-----
```php
$wsdl = 'http://www.webservicex.net/CurrencyConvertor.asmx?WSDL';

// cache_path must be writeable by the web server.
$cache_path = '/cache/'; // Optional. Defaults to './wsdl_cache/'.

$Client = new \alc\SecureSoapClient($wsdl, $cache_path);

$Params = array(
    'FromCurrency' => 'GBP',
    'ToCurrency' => 'USD'
);
$Response = $Client->ConversionRate($Params);
$Rate = $Response->ConversionRateResult;
```

Found a bug?
============
If you can fix it, please do.  Submit a pull request with your fix and phpunit tests that identify the bug.

If you can't fix it (or don't want to right now) please submit an issue report here on GitHub.

Want to help?
=============
If you want to help improve this please submit a pull request.

