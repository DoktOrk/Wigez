<?php

namespace Project\Application\Http\Controllers;

use Opulence\Orm\IUnitOfWork;
use Opulence\Routing\Urls\UrlGenerator;
use Project\Application\Grid\Factory\Page as GridFactory;
use Project\Application\Validation\Factory\Page as ValidatorFactory;
use Project\Domain\Entities\Page as Entity;
use Project\Domain\Orm\PageRepo as Repo;
use Project\Domain\Entities\IStringerEntity;

class Page extends CrudAbstract
{
    const ENTITY_SINGULAR = 'page';
    const ENTITY_PLURAL   = 'pages';

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
     * @param UrlGenerator          $urlGenerator
     * @param GridFactory           $gridFactory
     * @param Repo                  $repo
     * @param ValidatorFactory|null $validatorFactory
     * @param IUnitOfWork|null      $unitOfWork
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        GridFactory $gridFactory,
        Repo $repo,
        ValidatorFactory $validatorFactory = null,
        IUnitOfWork $unitOfWork = null
    ) {
        parent::__construct($urlGenerator, $gridFactory, $repo, $validatorFactory, $unitOfWork);
    }

    /**
     * @param int|null $id
     *
     * @return Entity
     */
    protected function createEntity(int $id = null): IStringerEntity
    {
        $id    = (int)$id;
        $title = '';
        $body  = '';

        $entity = new Entity($id, $title, $body);

        return $entity;
    }

    /**
     * @param Entity $entity
     *
     * @return Entity
     */
    protected function fillEntity(IStringerEntity $entity): IStringerEntity
    {
        $post = $this->request->getPost()->getAll();

        $title = (string)$post['title'];
        $body  = (string)$post['body'];

        $entity->setBody($body)->setTitle($title);

        return $entity;
    }
}
