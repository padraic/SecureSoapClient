[![Build Status](https://travis-ci.org/hades200082/SecureSoapClient.png)](https://travis-ci.org/hades200082/SecureSoapClient)

SecureSoapClient
================

The SecureSoapClient class is an extension of the native PHP SoapClient that uses cURL to make requests.

This ensures that any requests over **https** are actually secure by default.

The class otherwise acts in exactly the same way as the native SoapClient.

Requirements
------------
* PHP 5.3 or higher
* cURL (tested on 7.21.7)

Install
-------

### Via Composer ###
Just add the following to the require section of your composer.json file

```json
"require": {
    //...
    "hades200082/secure-soap-client": "1.0.0",
    //...
}
```

This will install the version 1.0.0 tag.  If you want the very latest bleeding edge version change `"1.0.0"` to `dev-master`

* https://packagist.org/packages/hades200082/secure-soap-client

### Via Direct Download ###
Download the [SecureSoapClient.php](https://github.com/hades200082/SecureSoapClient/blob/master/alc/SecureSoapClient.php) file from the repository and include it in your project.

Usage
-----
You just instantiate this class instead.

```php
$SoapClient = new \alc\SecureSoapClient($wsdl, $wsdl_cache_dir, $options);
```

Then use it just like the native PHP `SoapClient` class.  

* http://php.net/manual/en/class.soapclient.php
* http://php.net/manual/en/soapclient.soapclient.php

Found a bug?
============
If you can fix it, please do.  Submit a pull request with your fix and phpunit tests that identify the bug.

If you can't fix it (or don't want to right now) please submit an issue report here on GitHub.

Want to help?
=============
If you want to help improve this please submit a pull request.