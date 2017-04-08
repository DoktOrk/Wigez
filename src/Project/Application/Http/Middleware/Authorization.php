<?php

namespace Project\Application\Http\Middleware;

use Closure;
use Opulence\Http\Requests\Request;
use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;

class Authorization extends Session
{
    const CUSTOMER_PATH = '/admin/file';

    // $next consists of the next middleware in the pipeline
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->session->get(SESSION_IS_USER)) {
            return $next($request);
        }

        if ($request->getPath() === static::CUSTOMER_PATH) {
            return $next($request);
        }

        return new RedirectResponse(static::CUSTOMER_PATH, ResponseHeaders::HTTP_TEMPORARY_REDIRECT);
    }
}
