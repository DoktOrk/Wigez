<?php

namespace Project\Application\Http\Controllers;

use Opulence\Routing\Urls\UrlGenerator;
use Project\Application\Grid\Factory\Page as GridFactory;
use Project\Domain\Orm\PageRepo as Repo;

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
