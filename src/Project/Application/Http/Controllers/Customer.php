<?php

namespace Project\Application\Http\Controllers;

use Foo\I18n\ITranslator;
use Foo\Session\FlashService;
use Opulence\Http\Responses\Response;
use Opulence\Orm\IUnitOfWork;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Project\Application\Grid\Factory\Customer as GridFactory;
use Project\Application\Validation\Factory\Customer as ValidatorFactory;
use Project\Domain\Entities\Category;
use Project\Domain\Entities\Customer as Entity;
use Project\Domain\Entities\IStringerEntity;
use Project\Infrastructure\Orm\CategoryRepo;
use Project\Infrastructure\Orm\CustomerRepo as Repo;

class Customer extends CrudAbstract
{
    const ENTITY_SINGULAR = 'customer';
    const ENTITY_PLURAL   = 'customers';

    const ENTITY_TITLE_SINGULAR = 'application:customer';
    const ENTITY_TITLE_PLURAL   = 'application:customers';

    const VAR_ALL_CATEGORIES     = 'allCategories';
    const VAR_CURRENT_CATEGORIES = 'currentCategories';

    /** @var GridFactory */
    protected $gridFactory;

    /** @var Repo */
    protected $repo;

    /** @var Entity */
    protected $entity;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var ValidatorFactory */
    protected $validatorFactory;

    /** @var CategoryRepo */
    protected $categoryRepo;

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
        CategoryRepo $categoryRepo
    ) {
        $this->categoryRepo = $categoryRepo;

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
    public function new(): Response
    {
        $this->createEntity();

        $this->addCategories();

        return parent::new();
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function edit(int $id): Response
    {
        $this->retrieveEntity($id);

        $this->addCategories();

        return parent::edit($id);
    }

    /**
     * @param int|null $id
     *
     * @return Entity
     */
    public function createEntity(int $id = null): IStringerEntity
    {
        $id         = (int)$id;
        $name       = '';
        $email      = '';
        $password   = '';
        $categories = [];

        $this->entity = new Entity($id, $name, $email, $categories, $password);

        return $this->entity;
    }

    public function fillEntity(IStringerEntity $entity): IStringerEntity
    {
        $post = $this->request->getPost()->getAll();

        $name     = (string)$post['name'];
        $email    = (string)$post['email'];
        $password = \password_hash((string)$post['password'], PASSWORD_DEFAULT);

        $categories = [];
        if (isset($post['categories'])) {
            foreach ($post['categories'] as $categoryId) {
                $categories[] = new Category((int)$categoryId, '');
            }
        }

        $entity
            ->setName($name)
            ->setEmail($email)
            ->setPassword($password)
            ->setCategories($categories);

        return $entity;
    }

    /*
     * @param Entity $entity
     *
     * @return Entity
     */

    protected function addCategories()
    {
        $categoryIds = [];
        foreach ($this->entity->getCategories() as $category) {
            $categoryIds[] = $category->getId();
        }

        $this->viewVarsExtra[static::VAR_CURRENT_CATEGORIES] = $categoryIds;
        $this->viewVarsExtra[static::VAR_ALL_CATEGORIES]     = $this->categoryRepo->getAll();
    }
}
