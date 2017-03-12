<?php
namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\Response;
use Opulence\Routing\Controller;

/**
 * Defines an example controller
 */
class Index extends Controller
{
    /**
     * Shows the homepage
     *
     * @return Response The response
     */
    public function showHomePage() : Response
    {
        $this->view = $this->viewFactory->createView('contents/website/home');

        return new Response($this->viewCompiler->compile($this->view));
    }
}
