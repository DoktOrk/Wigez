<?php

namespace Project\Application\Http\Controllers;

use Opulence\Routing\Urls\UrlGenerator;
use Project\Domain\Orm\FileRepo as Repo;
use Project\Application\Grid\Factory\File as GridFactory;

class File extends CrudAbstract
{
    const ENTITY_SINGULAR = 'file';
    const ENTITY_PLURAL   = 'files';

    /** @var GridFactory */
    protected $gridFactory;

    /** @var Repo */
    protected $repo;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /**
     * Helps DIC figure out the dependencies
     *
     * @param UrlGenerator $urlGenerator
     * @param GridFactory  $gridFactory
     * @param Repo         $repo
     */
    public function __construct(UrlGenerator $urlGenerator, GridFactory $gridFactory, Repo $repo)
    {
        parent::__construct($urlGenerator, $gridFactory, $repo);
    }
}
