<?php
namespace Project\Application\Http\Controllers;

use Opulence\Routing\Urls\UrlGenerator;
use Project\Application\Grid\Factory\Customer as GridFactory;
use Project\Domain\Orm\CustomerRepo as Repo;

class Customer extends CrudAbstract
{
    const ENTITY_SINGULAR = 'customer';
    const ENTITY_PLURAL   = 'customers';

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
