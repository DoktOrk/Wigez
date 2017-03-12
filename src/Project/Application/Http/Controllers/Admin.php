<?php
namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\Response;
use Opulence\Routing\Controller;

class Admin extends Controller
{
    /**
     * Shows a random number on this page
     *
     * @return Response
     */
    public function showDashboardPage() : Response
    {
        $this->view = $this->viewFactory->createView('contents/admin/random');

        $this->view->setVar('title', 'Random');
        $this->view->setVar('random', rand(0, 20));

        return new Response($this->viewCompiler->compile($this->view));
    }

    /**
     * Shows a random number on this page
     *
     * @return Response
     */
    public function showRandomPage() : Response
    {
        $this->view = $this->viewFactory->createView('contents/admin/random');

        $this->view->setVar('title', 'Random');
        $this->view->setVar('random', rand(0, 20));

        return new Response($this->viewCompiler->compile($this->view));
    }
}
