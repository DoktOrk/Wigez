<?php
namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Routing\Controller;

class User extends Controller
{
    /**
     * Shows the login page
     *
     * @return Response
     */
    public function showLoginPage() : Response
    {
        $this->view = $this->viewFactory->createView('contents/admin/login');

        return new Response($this->viewCompiler->compile($this->view));
    }

    /**
     * Shows a random number on this page
     *
     * @return Response
     */
    public function logoutAction() : Response
    {
        $response = new RedirectResponse('/login', ResponseHeaders::HTTP_FOUND);
        $response->send();
    }
}
