<?php

use Foo\Debug\Exceptions\Handlers\Whoops\ExceptionHandler;
use Foo\Debug\Exceptions\Handlers\Whoops\ExceptionRenderer;
use Opulence\Environments\Environment;
use Whoops\Run;

/**
 * ----------------------------------------------------------
 * Define the exception handler
 * ----------------------------------------------------------
 *
 * The last parameter lists any exceptions you do not want to log
 */
$isDevelopment = Environment::getVar('ENV_NAME') === Environment::DEVELOPMENT;

$exceptionRenderer = new ExceptionRenderer(new Run(), $isDevelopment);

return new ExceptionHandler(
    $logger,
    $exceptionRenderer,
    [
        HttpException::class
    ]
);
