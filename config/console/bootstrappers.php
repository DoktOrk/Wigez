<?php
use Opulence\Framework\Composer\Bootstrappers\ComposerBootstrapper;
use Opulence\Framework\Console\Bootstrappers\CommandsBootstrapper as OpulenceCommandsBootstrapper;
use Opulence\Framework\Console\Bootstrappers\RequestBootstrapper;
use Wigez\Application\Bootstrappers\Console\Commands\CommandsBootstrapper as WigezCommandsBootstrapper;
use Wigez\Application\Bootstrappers\Databases\SqlBootstrapper;
use Wigez\Application\Bootstrappers\Http\Routing\RouterBootstrapper;
use Wigez\Application\Bootstrappers\Http\Views\ViewBootstrapper;

/**
 * ----------------------------------------------------------
 * Define bootstrapper classes for a console application
 * ----------------------------------------------------------
 */
return [
    RouterBootstrapper::class,
    OpulenceCommandsBootstrapper::class,
    RequestBootstrapper::class,
    ComposerBootstrapper::class,
    ViewBootstrapper::class,
    WigezCommandsBootstrapper::class,
    SqlBootstrapper::class
];
