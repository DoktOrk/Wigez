<?php
namespace Project\Application\Bootstrappers\Http\Views;

use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Container;
use Opulence\Sessions\ISession;
use Opulence\Views\Factories\IViewFactory;
use Opulence\Views\IView;
use Project\Application\Http\Views\Builders\AdminBuilder;
use Project\Application\Http\Views\Builders\HtmlErrorBuilder;
use Project\Application\Http\Views\Builders\WebsiteBuilder;

/**
 * Defines the view builders bootstrapper
 */
class BuildersBootstrapper extends Bootstrapper
{
    /** @var Container */
    protected $container;

    /**
     * @return Container
     */
    protected function getContainer()
    {
        /** @var Container */
        global $container;

        if ($this->container instanceof Container) {
            $this->container = $container;
        }

        return $container;
    }

    /**
     * Registers view builders to the factory
     *
     * @param IViewFactory $viewFactory The view factory to use
     */
    public function run(IViewFactory $viewFactory)
    {
        $viewFactory->registerBuilder('layouts/website', function (IView $view) {
            /** @see WebsiteBuilder::build() */
            return (new WebsiteBuilder())->build($view);
        });
        $viewFactory->registerBuilder('layouts/admin', function (IView $view) {
            $session = $this->getContainer()->resolve(ISession::class);

            /** @see AdminBuilder::build() */
            return (new AdminBuilder($session))->build($view);
        });
        $viewFactory->registerBuilder('layouts/empty', function (IView $view) {
            /** @see AdminBuilder::build() */
            return (new WebsiteBuilder())->build($view);
        });
        $viewFactory->registerBuilder('errors/html/Error', function (IView $view) {
            /** @see HtmlErrorBuilder::build() */
            return (new HtmlErrorBuilder())->build($view);
        });
    }
}
