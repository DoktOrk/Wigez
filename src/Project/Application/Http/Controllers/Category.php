<?php

namespace Project\Application\Http\Controllers;

use Opulence\Routing\Urls\UrlGenerator;
use Project\Application\Grid\Factory\Category as GridFactory;
use Project\Domain\Orm\CategoryRepo as Repo;

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

    /**
     * @param UrlGenerator $urlGenerator
     * @param GridFactory  $gridFactory
     * @param Repo         $repo
     */
    public function __construct(UrlGenerator $urlGenerator, GridFactory $gridFactory, Repo $repo)
    {
        $this->urlGenerator = $urlGenerator;

        $this->gridFactory = $gridFactory;

        $this->repo = $repo;
    }
}
