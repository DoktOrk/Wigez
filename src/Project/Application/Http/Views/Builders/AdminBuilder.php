<?php

namespace Project\Application\Http\Views\Builders;

use Opulence\Sessions\ISession;
use Opulence\Views\Factories\IViewBuilder;
use Opulence\Views\IView;

/**
 * Defines the home view builder
 */
class AdminBuilder implements IViewBuilder
{
    /** @var ISession */
    protected $session;

    /**
     * AdminBuilder constructor.
     *
     * @param ISession $session
     */
    public function __construct(ISession $session)
    {
        $this->session = $session;
    }

    /**
     * @inheritdoc
     */
    public function build(IView $view): IView
    {
        $view->setVar('title', 'Admin');
        $view->setVar('username', $this->session->get(SESSION_USERNAME));
        $view->setVar('is_user', $this->session->get(SESSION_IS_USER));
        $view->setVar('is_customer', $this->session->get(SESSION_IS_CUSTOMER));

        return $view;
    }
}
