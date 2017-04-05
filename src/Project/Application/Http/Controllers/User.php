<?php

namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Routing\Controller;
use Opulence\Sessions\ISession;
use Project\Application\Auth\Authenticator;
use Project\Domain\Orm\CategoryRepo;

class User extends Controller
{
    const LOGIN_USER     = 'user';
    const LOGIN_CUSTOMER = 'customer';

    const POST_USERNAME = 'username';
    const POST_PASSWORD = 'password';

    const SESSION_USERNAME    = 'username';
    const SESSION_IS_USER     = 'is_user';
    const SESSION_IS_CUSTOMER = 'is_customer';

    /** @var ISession */
    private $session;

    /** @var Authenticator */
    private $authenticator;

    /** @var CategoryRepo */
    private $categoryRepo;

    /**
     * User constructor.
     *
     * @param ISession      $session
     * @param Authenticator $authenticator
     * @param CategoryRepo  $categoryRepo
     */
    public function __construct(ISession $session, Authenticator $authenticator, CategoryRepo $categoryRepo)
    {
        $this->session       = $session;
        $this->authenticator = $authenticator;
        $this->categoryRepo  = $categoryRepo;
    }

    /**
     * Shows the login page
     *
     * @return Response
     */
    public function showLoginPage(): Response
    {
        if ($this->session->get(static::POST_USERNAME)) {
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
        $username = (string)$this->request->getInput(static::POST_USERNAME);
        $password = (string)$this->request->getInput(static::POST_PASSWORD);

        if ($this->loginUser($username, $password) || $this->loginCustomer($username, $password)) {
            $this->session->set(static::SESSION_USERNAME, $username);

            $response = new RedirectResponse(PATH_ADMIN . PATH_DASHBOARD, ResponseHeaders::HTTP_FOUND);
            $response->send();
        } else {
            $response = new RedirectResponse(PATH_LOGIN);
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

    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    protected function loginUser(string $username, string $password): bool
    {
        $storedPassword = $this->authenticator->getUserPassword($username);
        if (!$storedPassword) {
            return false;
        }

        $result = $this->authenticator->canLogin($password, $storedPassword);
        if (!$result) {
            return false;
        }

        $this->session->set(static::SESSION_IS_USER, true);
        $this->session->set(static::SESSION_IS_CUSTOMER, false);

        return true;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    protected function loginCustomer(string $username, string $password): bool
    {
        $storedPassword = $this->authenticator->getCustomerPassword($username);
        if (!$storedPassword) {
            return false;
        }

        $result = $this->authenticator->canLogin($password, $storedPassword);
        if (!$result) {
            return false;
        }

        $this->session->set(static::SESSION_IS_USER, false);
        $this->session->set(static::SESSION_IS_CUSTOMER, true);

        return true;
    }
}
