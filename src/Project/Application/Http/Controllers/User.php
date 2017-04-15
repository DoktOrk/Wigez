<?php

namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Routing\Controller;
use Opulence\Sessions\ISession;
use Project\Application\Auth\Authenticator;
use Project\Domain\Entities\Customer;
use Project\Infrastructure\Orm\CategoryRepo;
use Project\Infrastructure\Orm\CustomerRepo;

class User extends Controller
{
    const LOGIN_USER     = 'user';
    const LOGIN_CUSTOMER = 'customer';

    const POST_USERNAME = 'username';
    const POST_PASSWORD = 'password';

    /** @var ISession */
    private $session;

    /** @var Authenticator */
    private $authenticator;

    /** @var CategoryRepo */
    private $categoryRepo;

    /** @var CustomerRepo */
    private $customerRepo;

    /**
     * User constructor.
     *
     * @param ISession      $session
     * @param Authenticator $authenticator
     * @param CategoryRepo  $categoryRepo
     * @param CustomerRepo  $customerRepo
     */
    public function __construct(
        ISession $session,
        Authenticator $authenticator,
        CategoryRepo $categoryRepo,
        CustomerRepo $customerRepo
    ) {
        $this->session       = $session;
        $this->authenticator = $authenticator;
        $this->categoryRepo  = $categoryRepo;
        $this->customerRepo  = $customerRepo;
    }

    /**
     * Shows the login page
     *
     * @return Response
     */
    public function loginForm(): Response
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
    public function login(): Response
    {
        $username = (string)$this->request->getInput(static::POST_USERNAME);
        $password = (string)$this->request->getInput(static::POST_PASSWORD);
        if ($this->loginUser($username, $password)) {
            $this->session->set(SESSION_USERNAME, $username);

            $response = new RedirectResponse(PATH_ADMIN . PATH_PAGES);
            $response->send();
        } elseif ($this->loginCustomer($username, $password)) {
            /** @var Customer $customer */
            $customer = $this->customerRepo->find($username);

            $categories = $this->categoryRepo->getByCustomer($customer);

            $this->session->set(SESSION_USERNAME, $username);
            $this->session->set(SESSION_CATEGORIES, $categories);

            $response = new RedirectResponse(PATH_ADMIN . PATH_FILES);
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
    public function logout(): Response
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

        $this->session->set(SESSION_IS_USER, true);
        $this->session->set(SESSION_IS_CUSTOMER, false);

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

        $this->session->set(SESSION_IS_USER, false);
        $this->session->set(SESSION_IS_CUSTOMER, true);

        return true;
    }
}
