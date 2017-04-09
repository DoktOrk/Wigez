<?php

namespace Project\Application\Http\Controllers;

use Opulence\Orm\IUnitOfWork;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Project\Application\Grid\Factory\Category as GridFactory;
use Project\Application\Validation\Factory\Category as ValidatorFactory;
use Project\Domain\Entities\Category as Entity;
use Project\Domain\Entities\IStringerEntity;
use Project\Infrastructure\Orm\CategoryRepo as Repo;

class Category extends CrudAbstract
{
    const ENTITY_SINGULAR = 'category';
    const ENTITY_PLURAL   = 'categories';

    /** @var GridFactory */
    protected $gridFactory;

    /** @var Repo */
    protected $repo;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var ValidatorFactory */
    protected $validatorFactory;

    /**
     * Helps DIC figure out the dependencies
     *
     * @param ISession         $session
     * @param UrlGenerator     $urlGenerator
     * @param GridFactory      $gridFactory
     * @param Repo             $repo
     * @param ValidatorFactory $validatorFactory
     * @param IUnitOfWork|null $unitOfWork
     */
    public function __construct(
        ISession $session,
        UrlGenerator $urlGenerator,
        GridFactory $gridFactory,
        Repo $repo,
        ValidatorFactory $validatorFactory,
        IUnitOfWork $unitOfWork = null
    ) {
        parent::__construct($session, $urlGenerator, $gridFactory, $repo, $validatorFactory, $unitOfWork);
    }

    /**
     * @param int|null $id
     *
     * @return Entity
     */
    public function createEntity(int $id = null): IStringerEntity
    {
        $id   = (int)$id;
        $name = '';

        $entity = new Entity($id, $name);

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

        $name = isset($post['name']) ? (string)$post['name'] : '';

        $entity->setName($name);

        return $entity;
    }
}
