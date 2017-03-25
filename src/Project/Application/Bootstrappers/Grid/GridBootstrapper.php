<?php
namespace Project\Application\Bootstrappers\Grid;

use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Project\Application\Grid\Factory\Page as PageGridFactory;

/**
 * Defines the ORM bootstrapper
 */
class GridBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /**
     * @inheritdoc
     */
    public function getBindings() : array
    {
        return [
            PageGridFactory::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        $container->bindInstance(PageGridFactory::class, new PageGridFactory());
    }
}
