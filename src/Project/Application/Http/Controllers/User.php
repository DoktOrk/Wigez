<?php

namespace Project\Application\Http\Controllers;

use Foo\I18n\ITranslator;
use Foo\Session\FlashService;
use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Orm\IUnitOfWork;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Opulence\Validation\Factories\ValidatorFactory;
use Project\Application\Auth\Authenticator;
use Project\Application\Grid\Factory\User as GridFactory;
use Project\Domain\Entities\Customer;
use Project\Domain\Entities\IStringerEntity;
use Project\Domain\Entities\User as Entity;
use Project\Infrastructure\Orm\CategoryRepo;
use Project\Infrastructure\Orm\CustomerRepo;
use Project\Infrastructure\Orm\UserRepo as Repo;

class User extends CrudAbstract
{
    const ENTITY_SINGULAR = 'user';
    const ENTITY_PLURAL   = 'users';

    const ENTITY_TITLE_SINGULAR = 'application:user';
    const ENTITY_TITLE_PLURAL   = 'application:users';

    const LOGIN_USER     = 'user';
    const LOGIN_CUSTOMER = 'customer';

    const POST_USERNAME = 'username';
    const POST_PASSWORD = 'password';

    /** @var Authenticator */
    private $authenticator;

    /** @var CategoryRepo */
    private $categoryRepo;

    /** @var CustomerRepo */
    private $customerRepo;

    /**
     * User constructor.
     *
     * @param ISession         $session
     * @param UrlGenerator     $urlGenerator
     * @param GridFactory      $gridFactory
     * @param Repo             $repo
     * @param ITranslator      $translator
     * @param FlashService     $flashService
     * @param ValidatorFactory $validatorFactory
     * @param IUnitOfWork|null $unitOfWork
     * @param Authenticator    $authenticator
     * @param CategoryRepo     $categoryRepo
     * @param CustomerRepo     $customerRepo
     */
    public function __construct(
        ISession $session,
        UrlGenerator $urlGenerator,
        GridFactory $gridFactory,
        Repo $repo,
        ITranslator $translator,
        FlashService $flashService,
        ValidatorFactory $validatorFactory,
        IUnitOfWork $unitOfWork = null,
        Authenticator $authenticator,
        CategoryRepo $categoryRepo,
        CustomerRepo $customerRepo
    ) {
        $this->session       = $session;
        $this->authenticator = $authenticator;
        $this->categoryRepo  = $categoryRepo;
        $this->customerRepo  = $customerRepo;

        parent::__construct(
            $session,
            $urlGenerator,
            $gridFactory,
            $repo,
            $translator,
            $flashService,
            $validatorFactory,
            $unitOfWork
        );
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

    /**
     * @param int|null $id
     *
     * @return Entity
     */
    public function createEntity(int $id = null): IStringerEntity
    {
        $id       = (int)$id;
        $username = '';
        $email    = '';
        $password = '';

        $entity = new Entity($id, $username, $email, $password);

        return $entity;
    }

    /*
     * @param Entity $entity
     *
     * @return Entity
     */
    public function fillEntity(IStringerEntity $entity): IStringerEntity
    {
        $post = $this->request->getPost()->getAll();

        $username = isset($post['username']) ? (string)$post['username'] : '';
        $password = isset($post['password']) ? (string)$post['password'] : '';
        $email    = isset($post['email']) ? (string)$post['email'] : '';

        $entity->setUsername($username)->setEmail($email);

        if ($password) {
            $entity->setPassword(\password_hash($username, PASSWORD_DEFAULT));
        }

        return $entity;
    }
}
