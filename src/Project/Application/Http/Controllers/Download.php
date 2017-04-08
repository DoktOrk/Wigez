<?php

namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\Response;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Project\Application\Grid\Factory\Download as GridFactory;
use Project\Domain\Entities\Customer;
use Project\Domain\Entities\Download as Entity;
use Project\Domain\Entities\File;
use Project\Domain\Entities\IStringerEntity;
use Project\Infrastructure\Orm\DownloadRepo as Repo;

class Download extends CrudAbstract
{
    const ENTITY_SINGULAR = 'download';
    const ENTITY_PLURAL   = 'downloads';

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
     */
    public function __construct(ISession $session, UrlGenerator $urlGenerator, GridFactory $gridFactory, Repo $repo)
    {
        parent::__construct($session, $urlGenerator, $gridFactory, $repo);
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
        $file     = new File(0, '', '');
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
