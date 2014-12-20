Composer Proxy
=========

[![Build Status](https://travis-ci.org/tomzx/composer-proxy.svg)](https://travis-ci.org/tomzx/composer-proxy)
[![Total Downloads](https://poser.pugx.org/tomzx/composer-proxy/downloads.svg)](https://packagist.org/packages/tomzx/composer-proxy)
[![Latest Stable Version](https://poser.pugx.org/tomzx/composer-proxy/v/stable.svg)](https://packagist.org/packages/tomzx/composer-proxy)
[![Latest Unstable Version](https://poser.pugx.org/tomzx/composer-proxy/v/unstable.svg)](https://packagist.org/packages/tomzx/composer-proxy)
[![License](https://poser.pugx.org/tomzx/composer-proxy/license.svg)](https://packagist.org/packages/tomzx/composer-proxy)

`Composer Proxy` is a lightweight service that uses [satis](https://github.com/composer/satis) to provide you with the means to cache remote packages.

`Composer Proxy` is expected to be used with [Composer Proxy Client](https://github.com/tomzx/composer-proxy-client), which is a small composer plugin which will inform the proxy of the packages to cache.

Requirements
------------

* You will need a server which can be reached by any machine on which you wish to use `Composer Proxy Client`.
* PHP 5.4 <=

Getting started
---------------

1. Configure your server to point to the `public` folder.
2. Rename config.php.dist to config.php and edit it to your liking.
	1. The important values you will want to change are `homepage` (the proxy address that will serve the packages), `repositories` (see satis' definition of `repositories`) and `require-depenedencies` (again, see satis' definition).
2. In the projects you want to use the proxy, follow the steps listed in the README of [Composer Proxy Client](https://github.com/tomzx/composer-proxy-client).

On your first request to the server you should see the configuration file get created, as well as `public/package.json`, `public/include` and `public/dist`.

TODO
----

Support satis cron job to fetch current packages specifications

Note
----

This is a work in progress. `Composer Proxy` and `Composer Proxy Client` are not ready to be used in production.


License
-------

The code is licensed under the [MIT license](http://choosealicense.com/licenses/mit/). See license.txt.