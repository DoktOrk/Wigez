<?php
namespace Project\Application\Http\Middleware;

use Closure;
use Opulence\Http\Requests\Request;
use Opulence\Http\Responses\RedirectResponse;
use Opulence\Http\Responses\Response;
use Opulence\Routing\Middleware\IMiddleware;
use Project\Application\Auth\Authenticator;

class Authentication implements IMiddleware
{
    /** @var null|Authenticator */
    private $authenticator = null;

    // Inject any dependencies your middleware needs
    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    // $next consists of the next middleware in the pipeline
    public function handle(Request $request, Closure $next) : Response
    {
        if (!$this->authenticator->isLoggedIn()) {
            return new RedirectResponse('/login');
        }

        return $next($request);
    }
}
