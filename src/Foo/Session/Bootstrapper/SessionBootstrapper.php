<?php

namespace Foo\Session\Bootstrapper;

use Foo\Session\FlashService;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Sessions\ISession;

class SessionBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /**
     * @return array
     */
    public function getBindings() : array
    {
        return [
            FlashService::class,
        ];
    }
    /**
     * @param IContainer $container
     */
    public function registerBindings(IContainer $container)
    {
        $session = $container->resolve(ISession::class);

        $flashService = new FlashService($session);

        $container->bindInstance(FlashService::class, $flashService);
    }
}