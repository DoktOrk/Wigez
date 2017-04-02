<?php

namespace Project\Application\Bootstrappers\Grid;

use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Routing\Urls\UrlGenerator;
use Project\Application\Grid\Factory\Page as PageGridFactory;

/**
 * Defines the ORM bootstrapper
 */
class GridBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    protected $bindings = [
        PageGridFactory::class,
    ];

    /**
     * @inheritdoc
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        $urlResorver = $container->resolve(UrlGenerator::class);

        foreach ($this->bindings as $className) {
            $container->bindInstance($className, new $className($urlResorver));
        }
    }
}
