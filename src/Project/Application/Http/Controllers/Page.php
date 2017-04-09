<?php

namespace Project\Application\Http\Controllers;

use Foo\I18n\ITranslator;
use Opulence\Orm\IUnitOfWork;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Project\Application\Grid\Factory\Page as GridFactory;
use Project\Application\Validation\Factory\Page as ValidatorFactory;
use Project\Domain\Entities\IStringerEntity;
use Project\Domain\Entities\Page as Entity;
use Project\Infrastructure\Orm\PageRepo as Repo;

class Page extends CrudAbstract
{
    const ENTITY_SINGULAR = 'page';
    const ENTITY_PLURAL   = 'pages';

    const ENTITY_TITLE_SINGULAR = 'application:page';
    const ENTITY_TITLE_PLURAL   = 'application:pages';

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
     * @param ISession              $session
     * @param UrlGenerator          $urlGenerator
     * @param GridFactory           $gridFactory
     * @param Repo                  $repo
     * @param ITranslator           $translator
     * @param ValidatorFactory|null $validatorFactory
     * @param IUnitOfWork|null      $unitOfWork
     */
    public function __construct(
        ISession $session,
        UrlGenerator $urlGenerator,
        GridFactory $gridFactory,
        Repo $repo,
        ITranslator $translator,
        ValidatorFactory $validatorFactory = null,
        IUnitOfWork $unitOfWork = null
    ) {
        parent::__construct($session, $urlGenerator, $gridFactory, $repo, $translator, $validatorFactory, $unitOfWork);
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
