<?php
namespace Project\Application\Http\Views\Builders;

use Opulence\Views\Factories\IViewBuilder;
use Opulence\Views\IView;

/**
 * Defines the home view builder
 */
class LoginBuilder implements IViewBuilder
{
    /**
     * @inheritdoc
     */
    public function build(IView $view) : IView
    {
        $view->setVar('title', 'Login');

        return $view;
    }
}
