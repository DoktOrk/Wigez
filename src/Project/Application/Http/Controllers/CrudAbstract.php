<?php

namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Orm\Repositories\IRepository;
use Opulence\Routing\Controller;
use Opulence\Routing\Urls\UrlGenerator;
use Project\Application\Grid\Factory\IFactory;

abstract class CrudAbstract extends Controller
{
    const VIEW_LIST = 'contents/admin/grid';
    const VIEW_FORM = 'contents/admin/%s';

    const VAR_TITLE  = 'title';
    const VAR_GRID   = 'grid';
    const VAR_ROUTE  = 'route';

    const ENTITY_SINGULAR = '';
    const ENTITY_PLURAL   = '';

    const TITLE_SHOW = 'List %s';
    const TITLE_EDIT = 'Edit %s: %s';
    const TITLE_NEW  = 'Create new %s';

    /** @var IFactory */
    protected $gridFactory;

    /** @var IRepository */
    protected $repo;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /**
     * @param UrlGenerator $urlGenerator
     * @param IFactory     $gridFactory
     * @param IRepository  $repo
     */
    public function __construct(UrlGenerator $urlGenerator, IFactory $gridFactory, IRepository $repo)
    {
        $this->urlGenerator = $urlGenerator;

        $this->gridFactory = $gridFactory;

        $this->repo = $repo;
    }

    /**
     * @return Response
     */
    public function show(): Response
    {
        $pages = $this->repo->getAll();
        $grid = $this->gridFactory->createGrid($pages);

        $this->view = $this->viewFactory->createView(static::VIEW_LIST);
        $this->view->setVar(static::VAR_TITLE, sprintf(static::TITLE_SHOW, static::ENTITY_PLURAL));
        $this->view->setVar(static::VAR_GRID, $grid);

        return new Response($this->viewCompiler->compile($this->view));
    }

    /**
     * @return Response
     */
    public function new(): Response
    {
        $url = $this->urlGenerator->createFromName(sprintf('%s-new', static::ENTITY_PLURAL), 0);

        $this->view = $this->viewFactory->createView(sprintf(static::VIEW_FORM, static::ENTITY_SINGULAR));
        $this->view->setVar(static::VAR_TITLE, sprintf(static::TITLE_NEW, static::ENTITY_SINGULAR));
        $this->view->setVar(static::VAR_ROUTE, $url);

        return new Response($this->viewCompiler->compile($this->view));
    }

    /**
     * @return Response
     */
    public function edit(): Response
    {
        $url = $this->urlGenerator->createFromName(sprintf('%s-edit', static::ENTITY_PLURAL), 0);

        $this->view = $this->viewFactory->createView(sprintf(static::VIEW_FORM, static::ENTITY_SINGULAR));
        $this->view->setVar(static::VAR_TITLE, sprintf(static::TITLE_EDIT, static::ENTITY_SINGULAR, 0));
        $this->view->setVar(static::VAR_ROUTE, $url);

        return new Response($this->viewCompiler->compile($this->view));
    }

    /**
     * @return Response
     */
    public function delete(): Response
    {
        $url = $this->urlGenerator->createFromName(sprintf('%s-delete', static::ENTITY_PLURAL), 0);

        $response = new RedirectResponse($url, ResponseHeaders::HTTP_FOUND);
        $response->send();

        return $response;
    }
}
