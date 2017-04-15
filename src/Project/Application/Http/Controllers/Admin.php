<?php

namespace Project\Application\Http\Controllers;

use Foo\I18n\ITranslator;
use Foo\Session\FlashService;
use Opulence\Http\Responses\Response;

class Admin extends ControllerAbstract
{
    const TITLE_DASHBOARD = 'application:dashboard';

    /** @var ITranslator */
    protected $translator;

    /**
     * Helps DIC figure out the dependencies
     *
     * @param ITranslator  $translator
     * @param FlashService $flashService
     */
    public function __construct(ITranslator $translator, FlashService $flashService)
    {
        $this->translator = $translator;

        parent::__construct($flashService);
    }

    /**
     * @return Response
     */
    public function showDashboard(): Response
    {
        $title = $this->translator->translate(static::TITLE_DASHBOARD);

        $this->view = $this->viewFactory->createView('contents/admin/dashboard');

        return $this->createResponse($title);
    }
}
