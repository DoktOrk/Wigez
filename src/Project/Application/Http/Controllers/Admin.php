<?php
namespace Project\Application\Http\Controllers;

use Opulence\Http\Responses\Response;
use Opulence\Routing\Controller;

class Admin extends Controller
{
    /**
     * Shows a random number on this page
     *
     * @return Response
     */
    public function showDashboardPage() : Response
    {
        $this->view = $this->viewFactory->createView('contents/admin/dashboard');

        return new Response($this->viewCompiler->compile($this->view));
    }
}
