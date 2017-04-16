<?php

namespace Wigez\Application\Http\Controllers;

use Foo\I18n\ITranslator;
use Foo\Session\FlashService;
use Opulence\Orm\IUnitOfWork;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Wigez\Application\Grid\Factory\Category as GridFactory;
use Wigez\Application\Validation\Factory\Category as ValidatorFactory;
use Wigez\Domain\Entities\Category as Entity;
use Wigez\Domain\Entities\IStringerEntity;
use Wigez\Infrastructure\Orm\CategoryRepo as Repo;

class Category extends CrudAbstract
{
    const ENTITY_SINGULAR = 'category';
    const ENTITY_PLURAL   = 'categories';

    const ENTITY_TITLE_SINGULAR = 'application:category';
    const ENTITY_TITLE_PLURAL   = 'application:categories';

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
     * @param ITranslator      $translator
     * @param FlashService     $flashService
     * @param ValidatorFactory $validatorFactory
     * @param IUnitOfWork|null $unitOfWork
     */
    public function __construct(
        ISession $session,
        UrlGenerator $urlGenerator,
        GridFactory $gridFactory,
        Repo $repo,
        ITranslator $translator,
        FlashService $flashService,
        ValidatorFactory $validatorFactory,
        IUnitOfWork $unitOfWork = null
    ) {
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
