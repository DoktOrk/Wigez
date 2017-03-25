<?php

namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\Response;
use Opulence\Routing\Controller;
use Project\Domain\Orm\PageRepo;
use Project\Application\Grid\Factory\Page as GridFactory;

class Website extends Controller
{
    /** @var GridFactory */
    protected $gridFactory;

    /** @var PageRepo */
    protected $repo;

    /**
     * Website constructor.
     *
     * @param GridFactory $pageGridFactory
     * @param PageRepo $repo
     */
    public function __construct(GridFactory $pageGridFactory, PageRepo $repo)
    {
        $this->gridFactory = $pageGridFactory;

        $this->repo = $repo;
    }

    /**
     * Shows pages
     *
     * @return Response
     */
    public function showPagesPage(): Response
    {
        $pages = $this->repo->getAll();
        $grid = $this->gridFactory->createGrid($pages);

        $this->view = $this->viewFactory->createView('contents/admin/grid');

        $this->view->setVar('title', 'Random');
        $this->view->setVar('grid', $grid);

        return new Response($this->viewCompiler->compile($this->view));
    }

}
