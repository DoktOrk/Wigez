<?php
namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\Response;
use Opulence\Routing\Controller;

class File extends Controller
{
    /**
     * Shows categories
     *
     * @return Response
     */
    public function showCategoriesPage() : Response
    {
        $this->view = $this->viewFactory->createView('contents/admin/random');

        $this->view->setVar('title', 'Random');
        $this->view->setVar('random', rand(0, 20));

        return new Response($this->viewCompiler->compile($this->view));
    }

    /**
     * Shows customers
     *
     * @return Response
     */
    public function showCustomersPage() : Response
    {
        $this->view = $this->viewFactory->createView('contents/admin/random');

        $this->view->setVar('title', 'Random');
        $this->view->setVar('random', rand(0, 20));

        return new Response($this->viewCompiler->compile($this->view));
    }

    /**
     * Shows files
     *
     * @return Response
     */
    public function showFilesPage() : Response
    {
        $this->view = $this->viewFactory->createView('contents/admin/random');

        $this->view->setVar('title', 'Random');
        $this->view->setVar('random', rand(0, 20));

        return new Response($this->viewCompiler->compile($this->view));
    }

    /**
     * Shows file downloads
     *
     * @return Response
     */
    public function showDownloadsPage() : Response
    {
        $this->view = $this->viewFactory->createView('contents/admin/random');

        $this->view->setVar('title', 'Random');
        $this->view->setVar('random', rand(0, 20));

        return new Response($this->viewCompiler->compile($this->view));
    }
}
