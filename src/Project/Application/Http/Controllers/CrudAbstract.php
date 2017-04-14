<?php

declare(strict_types=1);

namespace Project\Application\Http\Controllers;

use Foo\I18n\ITranslator;
use Foo\Session\FlashService;
use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Orm\IUnitOfWork;
use Opulence\Orm\OrmException;
use Opulence\Orm\Repositories\IRepository;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Opulence\Validation\Factories\IValidatorFactory;
use Opulence\Validation\IValidator;
use Project\Application\Grid\Factory\IFactory;
use Project\Domain\Entities\IStringerEntity;

abstract class CrudAbstract extends ControllerAbstract
{
    const VIEW_LIST = 'contents/admin/grid';
    const VIEW_FORM = 'contents/admin/form/%s';

    const VAR_GRID        = 'grid';
    const VAR_ROUTE       = 'route';
    const VAR_ENTITY      = 'entity';
    const VAR_SHOW_URL    = 'showUrl';
    const VAR_CREATE_URL  = 'createUrl';
    const VAR_METHOD      = 'method';

    const ENTITY_SINGULAR = '';
    const ENTITY_PLURAL   = '';

    const ENTITY_TITLE_SINGULAR = '';
    const ENTITY_TITLE_PLURAL   = '';

    const TITLE_SHOW = 'grid:titleList';
    const TITLE_NEW  = 'form:titleNew';
    const TITLE_EDIT = 'form:titleEdit';

    const IS_EDIT_UPDATE = true;
    const IS_EDIT_CREATE = false;
    const IS_EDIT_DELETE = false;

    const METHOD_POST = 'POST';
    const METHOD_PUT  = 'PUT';

    const INPUT_CONTINUE = 'continue';

    const URL_NEW    = '%s-new';
    const URL_EDIT   = '%s-edit';
    const URL_CREATE = '%s-create';

    const CREATE_SUCCESS      = 'New %s created successfully';
    const UPDATE_SUCCESS      = 'Updated %s successfully';
    const ENTITY_LOAD_FAILURE = 'Loading %s failed.';

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

    /** @var ISession */
    protected $session;

    /** @var array */
    protected $customerAccess = [];

    /** @var ITranslator */
    protected $translator;

    /** @var FlashService */
    protected $flashService;

    /**
     * @param ISession          $session
     * @param UrlGenerator      $urlGenerator
     * @param IFactory          $gridFactory
     * @param IRepository       $repo
     * @param ITranslator       $translator
     * @param FlashService      $flashService
     * @param IValidatorFactory $validatorFactory
     * @param IUnitOfWork       $unitOfWork
     */
    public function __construct(
        ISession $session,
        UrlGenerator $urlGenerator,
        IFactory $gridFactory,
        IRepository $repo,
        ITranslator $translator,
        FlashService $flashService,
        IValidatorFactory $validatorFactory = null,
        IUnitOfWork $unitOfWork = null
    ) {
        $this->session          = $session;
        $this->urlGenerator     = $urlGenerator;
        $this->gridFactory      = $gridFactory;
        $this->repo             = $repo;
        $this->translator       = $translator;
        $this->validatorFactory = $validatorFactory;
        $this->unitOfWork       = $unitOfWork;

        parent::__construct($flashService);
    }

    /**
     * @return Response
     */
    public function show(): Response
    {
        $pages = $this->repo->getAll();
        $grid  = $this->gridFactory->createGrid($pages);
        $title = $this->translator->translate(static::TITLE_SHOW, static::ENTITY_TITLE_PLURAL);

        $this->view = $this->viewFactory->createView(static::VIEW_LIST);
        $this->view->setVar(static::VAR_GRID, $grid);
        $this->view->setVar(static::VAR_CREATE_URL, $this->getCreateUrl());

        return $this->createResponse($title);
    }

    /**
     * @return Response
     */
    public function new(): Response
    {
        $this->entity = $this->createEntity();

        $url   = $this->urlGenerator->createFromName(sprintf(static::URL_NEW, static::ENTITY_PLURAL));
        $title = $this->translator->translate(static::TITLE_NEW, static::ENTITY_TITLE_SINGULAR);

        $this->view = $this->viewFactory->createView(sprintf(static::VIEW_FORM, static::ENTITY_SINGULAR));
        $this->view->setVar(static::VAR_ROUTE, $url);
        $this->view->setVar(static::VAR_ENTITY, $this->entity);
        $this->view->setVar(static::VAR_SHOW_URL, $this->getShowUrl());
        $this->view->setVar(static::VAR_METHOD, static::METHOD_POST);

        return $this->createResponse($title);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $isValid = $this->validateForm();

        if (!$isValid) {
            $this->flashService->mergeErrorMessages($this->validator->getErrors()->getAll());

            return $this->redirectToList(static::IS_EDIT_CREATE);
        }

        $this->entity = $this->createEntity();
        $this->entity = $this->fillEntity($this->entity);

        $this->repo->add($this->entity);

        $this->commitCreate();

        $this->flashService->mergeSuccessMessages([sprintf(static::CREATE_SUCCESS, static::ENTITY_SINGULAR)]);

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

        $title = $this->translator->translate(static::TITLE_EDIT, static::ENTITY_TITLE_SINGULAR, $entityName);

        $this->view = $this->viewFactory->createView(sprintf(static::VIEW_FORM, static::ENTITY_SINGULAR));
        $this->view->setVar(static::VAR_ROUTE, $this->getEditUrl($id));
        $this->view->setVar(static::VAR_ENTITY, $this->entity);
        $this->view->setVar(static::VAR_SHOW_URL, $this->getShowUrl());
        $this->view->setVar(static::VAR_METHOD, static::METHOD_PUT);

        return $this->createResponse($title);
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
            $this->flashService->mergeErrorMessages($this->validator->getErrors()->getAll());

            return $this->redirectToList(static::IS_EDIT_UPDATE, $id);
        }

        $this->entity = $this->retrieveEntity($id);
        $this->fillEntity($this->entity);

        $this->commitUpdate();

        $this->flashService->mergeSuccessMessages([sprintf(static::UPDATE_SUCCESS, static::ENTITY_SINGULAR)]);

        return $this->redirectToList(static::IS_EDIT_UPDATE, $id);
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
            $this->flashService->mergeErrorMessages([sprintf(static::ENTITY_LOAD_FAILURE, static::ENTITY_SINGULAR)]);

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
        $this->commitDelete();

        return $this->redirectToList(static::IS_EDIT_DELETE);
    }

    /**
     * @return bool
     */
    protected function validateForm(): bool
    {
        $postData = $this->getPostData();

        $this->validator = $this->validatorFactory->createValidator();

        $isValid = $this->validator->isValid($postData);

        return $isValid;
    }

    /**
     * @return array
     */
    protected function getPostData(): array
    {
        $postData = $this->request->getPost()->getAll();

        return $postData;
    }

    /**
     * @param bool     $isEdit
     * @param int|null $id
     *
     * @return Response
     */
    protected function redirectToList(bool $isEdit, int $id = null): Response
    {
        $continue = (bool)$this->request->getInput(static::INPUT_CONTINUE);

        $url = $this->getUrl($continue, $isEdit, $id);

        $response = new RedirectResponse($url);
        $response->send();

        return $response;
    }

    /**
     * @param bool     $continue
     * @param bool     $isEdit
     * @param int|null $id
     *
     * @return string
     */
    protected function getUrl(bool $continue, bool $isEdit, int $id = null)
    {
        if (!$continue) {
            return $this->getShowUrl();
        } elseif ($isEdit) {
            return $this->getEditUrl($id);
        }

        return $this->getCreateUrl();
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
        $url = $this->urlGenerator->createFromName(sprintf(static::URL_EDIT, static::ENTITY_PLURAL), $id);

        return $url;
    }

    /**
     * @return string
     */
    protected function getCreateUrl(): string
    {
        $url = $this->urlGenerator->createFromName(sprintf(static::URL_CREATE, static::ENTITY_PLURAL));

        return $url;
    }

    public function commitUpdate()
    {
        $this->unitOfWork->commit();
    }

    public function commitCreate()
    {
        $this->unitOfWork->commit();
    }

    public function commitDelete()
    {
        $this->unitOfWork->commit();
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
