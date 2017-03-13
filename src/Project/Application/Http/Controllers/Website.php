<?php
namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\Response;
use Opulence\Routing\Controller;

class Website extends Controller
{
    /**
     * Shows pages
     *
     * @return Response
     */
    public function showPagesPage() : Response
    {
        $this->view = $this->viewFactory->createView('contents/admin/random');

        $this->view->setVar('title', 'Random');
        $this->view->setVar('random', rand(0, 20));

        return new Response($this->viewCompiler->compile($this->view));
    }

}
