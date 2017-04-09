<?php

namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Orm\IUnitOfWork;
use Opulence\Orm\OrmException;
use Opulence\Orm\Repositories\IRepository;
use Opulence\Routing\Controller;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Opulence\Validation\Factories\IValidatorFactory;
use Opulence\Validation\IValidator;
use Project\Application\Grid\Factory\IFactory;
use Project\Domain\Entities\IStringerEntity;

abstract class CrudAbstract extends Controller
{
    const VIEW_LIST = 'contents/admin/grid';
    const VIEW_FORM = 'contents/admin/form/%s';

    const VAR_TITLE       = 'title';
    const VAR_GRID        = 'grid';
    const VAR_ROUTE       = 'route';
    const VAR_ENTITY      = 'entity';
    const VAR_SHOW_URL    = 'showUrl';
    const VAR_CREATE_URL  = 'createUrl';
    const VAR_METHOD      = 'method';
    const VAR_MSG_ERROR   = 'errorMessages';
    const VAR_MSG_SUCCESS = 'successMessages';

    const ENTITY_SINGULAR = '';
    const ENTITY_PLURAL   = '';

    const TITLE_SHOW = 'List %s';
    const TITLE_EDIT = 'Edit %s: "%s"';
    const TITLE_NEW  = 'Create new %s';

    const IS_EDIT_UPDATE = true;
    const IS_EDIT_CREATE = false;
    const IS_EDIT_DELETE = false;

    const METHOD_POST = 'POST';
    const METHOD_PUT  = 'PUT';

    const SESSION_ERROR   = 'error';
    const SESSION_SUCCESS = 'success';

    const INPUT_CONTINUE = 'continue';

    /** @var IFactory */
    protected $gridFactory;

    /** @var IRepository */
    protected $repo;

    /** @var IStringerEntity|null */
    protected $entity;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var IValidatorFactory|null */
    protected $validatorFactory = null;

    /** @var IValidator|null */
    protected $validator = null;

    /** @var IUnitOfWork|null */
    protected $unitOfWork = null;

    /** @var array */
    protected $viewVarsExtra = [];

    /** @var ISession */
    protected $session;

    /** @var array */
    protected $customerAccess = [];

    /**
     * @param ISession          $session
     * @param UrlGenerator      $urlGenerator
     * @param IFactory          $gridFactory
     * @param IRepository       $repo
     * @param IValidatorFactory $validatorFactory
     * @param IUnitOfWork       $unitOfWork
     */
    public function __construct(
        ISession $session,
        UrlGenerator $urlGenerator,
        IFactory $gridFactory,
        IRepository $repo,
        IValidatorFactory $validatorFactory = null,
        IUnitOfWork $unitOfWork = null
    ) {
        $this->session          = $session;
        $this->urlGenerator     = $urlGenerator;
        $this->gridFactory      = $gridFactory;
        $this->repo             = $repo;
        $this->validatorFactory = $validatorFactory;
        $this->unitOfWork       = $unitOfWork;
    }

    /**
     * @return Response
     */
    public function show(): Response
    {
        $pages = $this->repo->getAll();
        $grid  = $this->gridFactory->createGrid($pages);

        $this->view = $this->viewFactory->createView(static::VIEW_LIST);
        $this->view->setVar(static::VAR_TITLE, sprintf(static::TITLE_SHOW, static::ENTITY_PLURAL));
        $this->view->setVar(static::VAR_GRID, $grid);
        $this->view->setVar(static::VAR_CREATE_URL, $this->getCreateUrl());
        $this->view->setVar(static::VAR_MSG_ERROR, $this->session->get(static::SESSION_ERROR));
        $this->view->setVar(static::VAR_MSG_SUCCESS, $this->session->get(static::SESSION_SUCCESS));

        return $this->createResponse();
    }

    /**
     * @return Response
     */
    public function new(): Response
    {
        $this->entity = $this->createEntity();

        $url = $this->urlGenerator->createFromName(sprintf('%s-new', static::ENTITY_PLURAL));

        $this->view = $this->viewFactory->createView(sprintf(static::VIEW_FORM, static::ENTITY_SINGULAR));
        $this->view->setVar(static::VAR_TITLE, sprintf(static::TITLE_NEW, static::ENTITY_SINGULAR));
        $this->view->setVar(static::VAR_ROUTE, $url);
        $this->view->setVar(static::VAR_ENTITY, $this->entity);
        $this->view->setVar(static::VAR_SHOW_URL, $this->getShowUrl());
        $this->view->setVar(static::VAR_METHOD, static::METHOD_POST);
        $this->view->setVar(static::VAR_MSG_ERROR, $this->session->get(static::SESSION_ERROR));
        $this->view->setVar(static::VAR_MSG_SUCCESS, $this->session->get(static::SESSION_SUCCESS));

        return $this->createResponse();
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $isValid = $this->validateForm();

        if (!$isValid) {
            $this->session->flash(static::SESSION_ERROR, $this->validator->getErrors()->getAll());

            return $this->redirectToList(static::IS_EDIT_CREATE);
        }

        $this->entity = $this->createEntity();
        $this->entity = $this->fillEntity($this->entity);

        $this->repo->add($this->entity);

        $this->unitOfWork->commit();

        $this->session->flash(static::SESSION_SUCCESS, ['Create okay']);

        return $this->redirectToList(static::IS_EDIT_CREATE);
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function edit(int $id): Response
    {
        $this->entity = $this->retrieveEntity($id);

        $entityName = (string)$this->entity;

        $this->view = $this->viewFactory->createView(sprintf(static::VIEW_FORM, static::ENTITY_SINGULAR));
        $this->view->setVar(static::VAR_TITLE, sprintf(static::TITLE_EDIT, static::ENTITY_SINGULAR, $entityName));
        $this->view->setVar(static::VAR_ROUTE, $this->getEditUrl($id));
        $this->view->setVar(static::VAR_ENTITY, $this->entity);
        $this->view->setVar(static::VAR_SHOW_URL, $this->getShowUrl());
        $this->view->setVar(static::VAR_METHOD, static::METHOD_PUT);
        $this->view->setVar(static::VAR_MSG_ERROR, $this->session->get(static::SESSION_ERROR));
        $this->view->setVar(static::VAR_MSG_SUCCESS, $this->session->get(static::SESSION_SUCCESS));

        return $this->createResponse();
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function update(int $id): Response
    {
        $isValid = $this->validateForm();

        if (!$isValid) {
            $this->session->flash(static::SESSION_ERROR, $this->validator->getErrors()->getAll());

            return $this->redirectToList(static::IS_EDIT_UPDATE, $id);
        }

        $this->entity = $this->retrieveEntity($id);
        $this->fillEntity($this->entity);

        $this->unitOfWork->commit();

        $this->session->flash(static::SESSION_SUCCESS, ['Update okay']);

        return $this->redirectToList(static::IS_EDIT_UPDATE, $id);
    }

    /**
     * @return Response
     */
    public function createResponse(): Response
    {
        foreach ($this->viewVarsExtra as $viewKey => $viewValue) {
            $this->view->setVar($viewKey, $viewValue);
        }

        return new Response($this->viewCompiler->compile($this->view));
    }

    /**
     * @param int|null $id
     *
     * @return IStringerEntity
     */
    public function retrieveEntity(int $id = null): IStringerEntity
    {
        try {
            /** @var IStringerEntity $entity */
            $this->entity = $this->repo->getById($id);
        } catch (OrmException $e) {
            $this->session->flash(static::SESSION_ERROR, ['Not loaded.']);

            return $this->createEntity();
        }

        return $this->entity;
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function delete(int $id): Response
    {
        $this->entity = $this->createEntity($id);

        $this->repo->delete($this->entity);
        $this->unitOfWork->commit();

        return $this->redirectToList(static::IS_EDIT_DELETE);
    }

    /**
     * @return bool
     */
    protected function validateForm(): bool
    {
        $postData = $this->request->getPost()->getAll();

        $this->validator = $this->validatorFactory->createValidator();

        $isValid = $this->validator->isValid($postData);

        return $isValid;
    }

    /**
     * @param bool     $isEdit
     * @param int|null $id
     *
     * @return Response
     */
    protected function redirectToList(bool $isEdit, int $id = null): Response
    {
        $continue = (int)$this->request->getInput(static::INPUT_CONTINUE);

        $url = '';
        if (!$continue) {
            $url = $this->getShowUrl();
        } elseif ($isEdit) {
            $url = $this->getEditUrl($id);
        } else {
            $url = $this->getCreateUrl();
        }

        $response = new RedirectResponse($url);
        $response->send();

        return $response;
    }

    /**
     * @return string
     */
    protected function getShowUrl(): string
    {
        $url = $this->urlGenerator->createFromName(static::ENTITY_PLURAL);

        return $url;
    }

    /**
     * @param int $id
     *
     * @return string
     */
    protected function getEditUrl(int $id): string
    {
        $url = $this->urlGenerator->createFromName(sprintf('%s-edit', static::ENTITY_PLURAL), $id);

        return $url;
    }

    /**
     * @return string
     */
    protected function getCreateUrl(): string
    {
        $url = $this->urlGenerator->createFromName(sprintf('%s-create', static::ENTITY_PLURAL));

        return $url;
    }

    /**
     * @param int|null $id
     *
     * @return IStringerEntity
     */
    abstract protected function createEntity(int $id = null): IStringerEntity;

    /**
     * @param int|null $id
     *
     * @return IStringerEntity
     */
    abstract protected function fillEntity(IStringerEntity $entity): IStringerEntity;
}
