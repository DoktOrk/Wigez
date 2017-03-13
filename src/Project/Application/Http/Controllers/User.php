<?php

namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Routing\Controller;
use Opulence\Sessions\ISession;
use Project\Application\Auth\Authenticator;

class User extends Controller
{
    /** @var ISession */
    private $session;

    /** @var Authenticator */
    private $authenticator;

    /**
     * User constructor.
     *
     * @param ISession $session
     * @param Authenticator $authenticator
     */
    public function __construct(ISession $session, Authenticator $authenticator)
    {
        $this->session = $session;
        $this->authenticator = $authenticator;
    }

    /**
     * Shows the login page
     *
     * @return Response
     */
    public function showLoginPage(): Response
    {
        if ($this->session->get('username')) {
            $response = new RedirectResponse(PATH_ADMIN . PATH_DASHBOARD);

            return $response;
        }

        $this->view = $this->viewFactory->createView('contents/login');

        return new Response($this->viewCompiler->compile($this->view));
    }

    /**
     * Shows a random number on this page
     *
     * @return Response
     */
    public function loginAction(): Response
    {
        $username = (string)$this->request->getInput('username');
        $password = (string)$this->request->getInput('password');

        if ($this->authenticator->canLogin($username, $password)) {
            $this->session->set('username', $username);
            $response = new RedirectResponse(PATH_ADMIN . PATH_DASHBOARD, ResponseHeaders::HTTP_FOUND);
            $response->send();
        } else {
            $response = new RedirectResponse(PATH_LOGIN, ResponseHeaders::HTTP_UNAUTHORIZED);
            $response->send();
        }

        return $response;
    }

    /**
     * Shows a random number on this page
     *
     * @return Response
     */
    public function logoutAction(): Response
    {
        $this->session->flush();

        $response = new RedirectResponse(PATH_LOGIN, ResponseHeaders::HTTP_FOUND);
        $response->send();

        return $response;
    }
}
