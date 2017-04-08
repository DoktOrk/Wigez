<?php
namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\Response;
use Opulence\Routing\Controller;
use Project\Infrastructure\Orm\PageRepo as Repo;

/**
 * Defines an example controller
 */
class Index extends Controller
{
    /** @var Repo */
    protected $repo;

    /**
     * @param Repo $repo
     */
    public function __construct(Repo $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Shows the homepage
     *
     * @return Response The response
     */
    public function showHomePage() : Response
    {
        $this->view = $this->viewFactory->createView('contents/website/home');

        $this->view->setVar('header', $this->repo->getById(1));
        $this->view->setVar('ugyvitel', $this->repo->getById(2));
        $this->view->setVar('importExport', $this->repo->getById(3));
        $this->view->setVar('tanacsadas', $this->repo->getById(4));
        $this->view->setVar('szoftver', $this->repo->getById(5));
        $this->view->setVar('kapcsolat', $this->repo->getById(6));

        return new Response($this->viewCompiler->compile($this->view));
    }
}
