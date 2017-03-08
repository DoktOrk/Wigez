<?php
namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\Response;
use Opulence\Routing\Controller;

class Admin extends Controller
{
    /**
     * Shows the login page
     *
     * @return Response The response
     */
    public function showLoginPage() : Response
    {
        $this->view = $this->viewFactory->createView('Login');

        return new Response($this->viewCompiler->compile($this->view));
    }

    /**
     * Shows a random number on this page
     *
     * @return Response The response
     */
    public function showRandomPage() : Response
    {
        $this->view = $this->viewFactory->createView('Random');

        $this->view->setVar('title', 'Random');
        $this->view->setVar('random', rand(0, 20));

        return new Response($this->viewCompiler->compile($this->view));
    }
}
