<?php
namespace Project\Application\Http\Controllers;

use Opulence\Routing\Urls\UrlGenerator;
use Project\Domain\Orm\DownloadRepo as Repo;
use Project\Application\Grid\Factory\Download as GridFactory;

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
     * @param UrlGenerator $urlGenerator
     * @param GridFactory  $gridFactory
     * @param Repo         $repo
     */
    public function __construct(UrlGenerator $urlGenerator, GridFactory $gridFactory, Repo $repo)
    {
        parent::__construct($urlGenerator, $gridFactory, $repo);
    }
}
