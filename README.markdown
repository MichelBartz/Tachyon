# Tachyon framework for PHP5.3+

Tachyon is a PHP micro framework inspired by [web.py](http://webpy.org) and by the the [MicroPHP](http://microphp.org/) initiative released under the MIT public license.

## Features

Tachyon provides just the necessary tools for creating websites in PHP :

* Route handling RegEx style
* Parameters in URI
* Closure as Controller
* Class as Controller
* An optional Controller abstract
* HTTP Caching easiness.
* HTTP Header control.
* PHP 5.3+
* 404 Handler.

## Requirements

* PHP 5.3>=
* PHPUnit (If you want to run the tests)
* Apache 2+ or Nginx (example Nginx rewrite in examples/nginx.conf)

## "Hello World" (PHP >= 5.3)

If you are familiar with web.py, this will look like home.

    require "Tachyon/Application.php"

	$urls = array("/(.*)" => function() {
							     echo "Hello World!";
		                     });

	$app = new \Tachyon\Application($urls);
	$app->run();
