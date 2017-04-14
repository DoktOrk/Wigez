<?php

namespace Project\Application\Http\Controllers;

use Foo\Filesystem\Uploader\Uploader;
use Foo\I18n\ITranslator;
use Foo\Session\FlashService;
use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Http\Responses\StreamResponse;
use Opulence\Orm\IUnitOfWork;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Project\Application\Grid\Factory\File as GridFactory;
use Project\Application\Validation\Factory\File as ValidatorFactory;
use Project\Domain\Entities\Category;
use Project\Domain\Entities\File as Entity;
use Project\Domain\Entities\IStringerEntity;
use Project\Infrastructure\Orm\CategoryRepo;
use Project\Infrastructure\Orm\FileRepo as Repo;

class File extends CrudAbstract
{
    const ENTITY_SINGULAR = 'file';
    const ENTITY_PLURAL   = 'files';

    const ENTITY_TITLE_SINGULAR = 'application:file';
    const ENTITY_TITLE_PLURAL   = 'application:files';

    const VAR_CATEGORIES = 'categories';

    const FILE_FILE     = 'file';
    const FILE_FILENAME = 'filename';

    /** @var GridFactory */
    protected $gridFactory;

    /** @var Repo */
    protected $repo;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var ValidatorFactory */
    protected $validatorFactory;

    /** @var CategoryRepo */
    protected $categoryRepo;

    /** @var Uploader */
    protected $uploader;

    /** @var Entity */
    protected $entity;

    /**
     * Helps DIC figure out the dependencies
     *
     * @param ISession         $session
     * @param UrlGenerator     $urlGenerator
     * @param GridFactory      $gridFactory
     * @param Repo             $repo
     * @param ITranslator      $translator
     * @param FlashService     $flashService
     * @param ValidatorFactory $validatorFactory
     * @param IUnitOfWork      $unitOfWork
     * @param CategoryRepo     $categoryRepo
     * @param Uploader         $uploader
     */
    public function __construct(
        ISession $session,
        UrlGenerator $urlGenerator,
        GridFactory $gridFactory,
        Repo $repo,
        ITranslator $translator,
        FlashService $flashService,
        ValidatorFactory $validatorFactory,
        IUnitOfWork $unitOfWork,
        CategoryRepo $categoryRepo,
        Uploader $uploader
    ) {
        $this->categoryRepo = $categoryRepo;
        $this->uploader     = $uploader;

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
     * @return Response
     */
    public function show(): Response
    {
        if ($this->session->get(SESSION_IS_USER)) {
            return parent::show();
        }

        return $this->showLimited();
    }

    /**
     * @return bool
     */
    protected function validateForm(): bool
    {
        $this->upload();

        $postData = $this->getPostData();

        $this->validator = $this->validatorFactory->createValidator();

        $this->uploader->field(static::FILE_FILE);

        $isValid = $this->validator->isValid($postData) && $this->uploader->isValid($_FILES);

        return $isValid;
    }

    public function upload()
    {
        $this->uploader->field(static::FILE_FILE);

        if (!$this->uploader->isValid($_FILES)) {
            $this->flashService->mergeErrorMessages($this->uploader->getErrors()->getAll());
        }
    }

    /**
     * @return Response
     */
    public function showLimited(): Response
    {
        $pages = $this->repo->getByCategories($this->session->get(SESSION_CATEGORIES));
        $grid  = $this->gridFactory->createGrid($pages);
        $title = sprintf(static::TITLE_SHOW, static::ENTITY_PLURAL);

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
        $this->addAllCategories();

        return parent::new();
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function edit(int $id): Response
    {
        $this->addAllCategories();

        return parent::edit($id);
    }

    /**
     * @param int|null $id
     *
     * @return Entity
     */
    public function createEntity(int $id = null): IStringerEntity
    {
        $id          = (int)$id;
        $file        = '';
        $filename    = '';
        $description = '';
        $uploadedAt  = new \DateTime();
        $category    = new Category(0, '');

        $entity = new Entity($id, $file, $filename, $description, $category, $uploadedAt);

        return $entity;
    }

    /**
     * @param Entity $entity
     *
     * @return Entity
     */
    public function fillEntity(IStringerEntity $entity): IStringerEntity
    {
        $post = $this->getPostData();

        $description = (string)$post['description'];

        /** @var Category $category */
        $category = $this->categoryRepo->getById($post['category']);

        $entity
            ->setDescription($description)
            ->setCategory($category);

        if (array_key_exists(static::FILE_FILE, $post)) {
            $entity
                ->setFile((string)$post[static::FILE_FILE])
                ->setFilename((string)$post[static::FILE_FILENAME]);
        }

        return $entity;
    }

    /**
     * @return array
     */
    protected function getPostData(): array
    {
        $postData = $this->request->getPost()->getAll();

        $uploadInfo = $this->uploader->getUploadInfo(static::FILE_FILE);
        if ($uploadInfo->isValid()) {
            $postData[static::FILE_FILE]     = $uploadInfo->getFileName();
            $postData[static::FILE_FILENAME] = $uploadInfo->getRawName();
        }

        return $postData;
    }

    /*
     * @param Entity $entity
     *
     * @return Entity
     */
    protected function addAllCategories()
    {
        $this->viewVarsExtra[static::VAR_CATEGORIES] = $this->categoryRepo->getAll();
    }

    public function commitUpdate()
    {
        if ($this->entity->isFileUploaded()) {
            $this->deleteFile();
        }

        $this->unitOfWork->commit();

        $this->uploadFile();
    }

    public function commitCreate()
    {
        $this->unitOfWork->commit();

        $this->uploadFile();
    }

    public function commitDelete()
    {
        $this->entity = $this->retrieveEntity($this->entity->getId());

        $this->deleteFile();

        $this->unitOfWork->commit();
    }

    private function uploadFile()
    {
        if ($this->uploader->persistAll()) {
            $this->flashService->mergeSuccessMessages($this->uploader->getSuccessMessages());
        } else {
            $this->flashService->mergeErrorMessages($this->uploader->getErrors()->getAll());
        }
    }

    private function deleteFile()
    {
        $file = $this->entity->getOldFile();
        if ($file) {
            $this->uploader->delete($file, static::FILE_FILE);
        }
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function download(int $id): Response
    {
        /** @var Entity $entity */
        $entity = $this->retrieveEntity($id);

        $stream = $this->uploader->getStream($entity->getFile(), static::FILE_FILE);

        if (!$stream) {
            return new RedirectResponse($this->getShowUrl());
        }

//        $headers = [
//            'Content-type' => 'application/octet-stream',
//            'Content-Transfer-Encoding' => '',
//            'Content-disposition' => 'attachment; filename=' . $entity->getFilename(),
//        ];
//
//        return new StreamResponse(
//            function() use($stream) {
//                while(!feof($stream)) {
//                    print(@fread($stream, 1024*8));
//                    ob_flush();
//                    flush();
//                }
//            },
//            ResponseHeaders::HTTP_OK,
//            $headers
//        );

        header('Content-type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header('Content-disposition: attachment; filename=' . $entity->getFilename());

        while(!feof($stream)) {
            print(@fread($stream, 1024*8));
            ob_flush();
            flush();
        }

        exit();
    }
}
