<?php

namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\Response;
use Opulence\Orm\IUnitOfWork;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Project\Application\Grid\Factory\File as GridFactory;
use Project\Application\Validation\Factory\File as ValidatorFactory;
use Project\Domain\Entities\Category;
use Project\Domain\Entities\File as Entity;
use Project\Domain\Entities\IStringerEntity;
use Project\Domain\Orm\CategoryRepo;
use Project\Domain\Orm\FileRepo as Repo;

class File extends CrudAbstract
{
    const ENTITY_SINGULAR = 'file';
    const ENTITY_PLURAL   = 'files';

    const VAR_CATEGORIES = 'categories';

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

    /**
     * Helps DIC figure out the dependencies
     *
     * @param ISession      $session
     * @param UrlGenerator     $urlGenerator
     * @param GridFactory      $gridFactory
     * @param Repo             $repo
     * @param ValidatorFactory $validatorFactory
     * @param IUnitOfWork      $unitOfWork
     * @param CategoryRepo     $categoryRepo
     */
    public function __construct(
        ISession $session,
        UrlGenerator $urlGenerator,
        GridFactory $gridFactory,
        Repo $repo,
        ValidatorFactory $validatorFactory,
        IUnitOfWork $unitOfWork,
        CategoryRepo $categoryRepo
    ) {
        $this->categoryRepo = $categoryRepo;

        parent::__construct($session, $urlGenerator, $gridFactory, $repo, $validatorFactory, $unitOfWork);
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

    protected function addAllCategories()
    {
        $this->viewVarsExtra[static::VAR_CATEGORIES] = $this->categoryRepo->getAll();
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
        $description = '';
        $uploadedAt  = new \DateTime();
        $category    = new Category(0, '');

        $entity = new Entity($id, $file, $description, $category, $uploadedAt);

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

        $file        = (string)$post['file'];
        $description = (string)$post['description'];
        $category    = $this->categoryRepo->getById($post['category']);

        $entity
            ->setFile($file)
            ->setDescription($description)
            ->setCategory($category)
        ;

        return $entity;
    }
}
