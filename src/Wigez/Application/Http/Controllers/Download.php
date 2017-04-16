<?php

namespace Wigez\Application\Http\Controllers;

use Foo\I18n\ITranslator;
use Foo\Session\FlashService;
use Opulence\Http\Responses\Response;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Wigez\Application\Grid\Factory\Download as GridFactory;
use Wigez\Domain\Entities\Customer;
use Wigez\Domain\Entities\Download as Entity;
use Wigez\Domain\Entities\File;
use Wigez\Domain\Entities\IStringerEntity;
use Wigez\Infrastructure\Orm\DownloadRepo as Repo;

class Download extends CrudAbstract
{
    const ENTITY_SINGULAR = 'download';
    const ENTITY_PLURAL   = 'downloads';

    const ENTITY_TITLE_SINGULAR = 'application:download';
    const ENTITY_TITLE_PLURAL   = 'application:downloads';

    /** @var GridFactory */
    protected $gridFactory;

    /** @var Repo */
    protected $repo;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /**
     * Helps DIC figure out the dependencies
     *
     * @param ISession     $session
     * @param UrlGenerator $urlGenerator
     * @param GridFactory  $gridFactory
     * @param Repo         $repo
     * @param ITranslator  $translator
     * @param FlashService $flashService
     */
    public function __construct(
        ISession $session,
        UrlGenerator $urlGenerator,
        GridFactory $gridFactory,
        Repo $repo,
        ITranslator $translator,
        FlashService $flashService
    ) {
        parent::__construct($session, $urlGenerator, $gridFactory, $repo, $translator, $flashService);
    }

    /**
     * @return Response
     */
    public function new(): Response
    {
        throw new \RuntimeException(sprintf('Route is not supported: %s.%s()', __CLASS__, __FILE__));
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function edit(int $id): Response
    {
        throw new \RuntimeException(sprintf('Route is not supported: %s.%s()', __CLASS__, __FILE__));
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function delete(int $id): Response
    {
        throw new \RuntimeException(sprintf('Route is not supported: %s.%s()', __CLASS__, __FILE__));
    }

    /**
     * @param int|null $id
     *
     * @return IStringerEntity
     */
    protected function createEntity(int $id = null): IStringerEntity
    {
        $file     = new File(0, '', '', '');
        $customer = new Customer(0, '', '', [], '');

        return new Entity(0, $file, $customer, new \DateTime());
    }

    /**
     * @param int|null $id
     *
     * @return IStringerEntity
     */
    protected function fillEntity(IStringerEntity $entity): IStringerEntity
    {
        return $entity;
    }

    /**
     * @return string
     */
    protected function getCreateUrl(): string
    {
        return '';
    }
}
