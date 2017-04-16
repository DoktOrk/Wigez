<?php

use Foo\I18n\Bootstrapper\I18nBootstrapper;
use Foo\Filesystem\Bootstrapper\FilesystemBootstrapper;
use Foo\Session\Bootstrapper\SessionBootstrapper;
use Opulence\Framework\Cryptography\Bootstrappers\CryptographyBootstrapper;
use Wigez\Application\Bootstrappers\Cache\RedisBootstrapper;
use Wigez\Application\Bootstrappers\Databases\SqlBootstrapper;
use Wigez\Application\Bootstrappers\Events\EventDispatcherBootstrapper;
use Wigez\Application\Bootstrappers\Orm\OrmBootstrapper;
use Wigez\Application\Bootstrappers\Validation\ValidatorBootstrapper;

/**
 * ----------------------------------------------------------
 * Define the bootstrapper classes for all applications
 * ----------------------------------------------------------
 */
return [
    CryptographyBootstrapper::class,
    EventDispatcherBootstrapper::class,
    SqlBootstrapper::class,
    RedisBootstrapper::class,
    OrmBootstrapper::class,
    ValidatorBootstrapper::class,
    SessionBootstrapper::class,
    FilesystemBootstrapper::class,
    I18nBootstrapper::class,
];
