<?php

namespace Foo\Filesystem\Bootstrapper;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Foo\Filesystem\Uploader\Uploader;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Validation\Rules\Factories\RulesFactory;
use Project\Application\Constant\Env;

class FilesystemBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /**
     * @return array
     */
    public function getBindings() : array
    {
        return [
            Filesystem::class,
            Uploader::class,
        ];
    }
    /**
     * @param IContainer $container
     */
    public function registerBindings(IContainer $container)
    {
        $dirPrivate = getenv(Env::DIR_PRIVATE);
        $adapter    = new Local($dirPrivate);
        $persister = new Filesystem($adapter);

        $container->bindInstance(Filesystem::class, $persister);

        $adapter = new Local('/');
        $reader = new Filesystem($adapter);

        /** @var RulesFactory $rulesFactory */
        $rulesFactory = $container->resolve(RulesFactory::class);

        $uploader = new Uploader($reader, $persister, $rulesFactory);

        $container->bindInstance(Uploader::class, $uploader);
    }
}
