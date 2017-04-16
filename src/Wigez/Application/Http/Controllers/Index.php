<?php

namespace Wigez\Application\Http\Controllers;

use Foo\Session\FlashService;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Orm\OrmException;
use Wigez\Infrastructure\Orm\PageRepo as Repo;

/**
 * Defines an example controller
 */
class Index extends ControllerAbstract
{
    const NOPE = 'nope.';

    /** @var Repo */
    protected $repo;

    /**
     * @param Repo         $repo
     * @param FlashService $flashService
     */
    public function __construct(Repo $repo, FlashService $flashService)
    {
        $this->repo = $repo;

        parent::__construct($flashService);
    }

    /**
     * Shows the homepage
     *
     * @return Response The response
     */
    public function homePage(): Response
    {
        $this->view = $this->viewFactory->createView('contents/website/home');

        $this->view->setVar('title', 'Ecomp.co.hu');

        $this->view->setVar('ugyvitel', '');
        $this->view->setVar('importExport', '');
        $this->view->setVar('tanacsadas', '');
        $this->view->setVar('szoftver', '');
        $this->view->setVar('kapcsolat', '');

        try {
            $this->view->setVar('ugyvitel', $this->repo->getByTitle('Ügyvitel')->getBody());
            $this->view->setVar('importExport', $this->repo->getByTitle('Import-Export')->getBody());
            $this->view->setVar('tanacsadas', $this->repo->getByTitle('Tanácsadás')->getBody());
            $this->view->setVar('szoftver', $this->repo->getByTitle('Szoftver')->getBody());
            $this->view->setVar('kapcsolat', $this->repo->getByTitle('Kapcsolat')->getBody());
        } catch (OrmException $e) {
        }

        return $this->createResponse('');
    }

    /**
     * Shows the homepage
     *
     * @return Response The response
     */
    public function nope(): Response
    {
        return new Response(static::NOPE, ResponseHeaders::HTTP_FORBIDDEN);
    }
}
