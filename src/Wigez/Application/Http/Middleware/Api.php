<?php

namespace Wigez\Application\Http\Middleware;

use Closure;
use Opulence\Http\Requests\Request;
use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Routing\Middleware\IMiddleware;
use Wigez\Application\Auth\Authenticator;

class Api implements IMiddleware
{
    /** @var Authenticator */
    protected $authenticator;

    /**
     * @param Authenticator $authenticator The session used by the application
     */
    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    // $next consists of the next middleware in the pipeline
    public function handle(Request $request, Closure $next): Response
    {
        $username = $request->getInput('username');
        $password = $request->getInput('password');

        if (is_null($username) || is_null($password)) {
            return new RedirectResponse(PATH_NOPE, ResponseHeaders::HTTP_TEMPORARY_REDIRECT);
        }

        $storedPassword = $this->authenticator->getUserPassword($username);
        if (!$storedPassword) {
            $storedPassword = $this->authenticator->getCustomerPassword($username);
        }

        if (!$this->authenticator->canLogin($password, $storedPassword)) {
            return new RedirectResponse(PATH_NOPE, ResponseHeaders::HTTP_TEMPORARY_REDIRECT);
        }

        return $next($request);
    }
}
