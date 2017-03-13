<?php
namespace Project\Application\Http\Middleware;

use Closure;
use Opulence\Http\Requests\Request;
use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;

class Authentication extends Session
{
    // $next consists of the next middleware in the pipeline
    public function handle(Request $request, Closure $next) : Response
    {
        if (!$this->session->get('username')) {
            return new RedirectResponse(PATH_LOGIN, ResponseHeaders::HTTP_TEMPORARY_REDIRECT);
        }

        return $next($request);
    }
}
