# Error Handling Library. 

[![Build Status](https://travis-ci.org/ArabCoders/errors.svg?branch=master)](https://travis-ci.org/ArabCoders/errors)

Error Handling Library, 
This library can handle logging and displaying of the errors,
you can also register listener for special error cases such as if you want to handle specific error/exception
in different way, or want to extend/replace the Structured data, 
It can log to multiple services defined by the user, it can also handle different output streams, 
there is also Policy class to handle Different requirement of logging/displaying/exiting of the application. 

## Install

Via Composer

```bash
$ composer require arabcoders/errors
```

## Usage Example.

```php
<?php

require __DIR__ . '/../../autoload.php';

$error = new \arabcoders\errors\Error();

$error->addLogger( 'default', new \arabcoders\errors\Logging\Syslog() )
      ->setOutput( new \arabcoders\errors\Output\HTML() );

//-- or you can use (bool)true in constructor to init default logger/output like
$error = new \arabcoders\errors\Error(true);

trigger_error( 'test Warning', \E_USER_WARNING);
```

## To run Tests.

```bash
cd vendor/arabcoders/errors;
composer update
./vendor/bin/phpunit tests/
```