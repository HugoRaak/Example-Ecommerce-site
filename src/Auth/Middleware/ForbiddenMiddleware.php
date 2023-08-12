<?php

declare(strict_types=1);

namespace App\Auth\Middleware;

use Framework\Auth\ForbiddenException;
use Framework\Response\RedirectResponse;
use Framework\Session\FlashService;
use Framework\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * catch forbiddenException and redirect the user
 */
final readonly class ForbiddenMiddleware implements MiddlewareInterface
{
    public function __construct(private string $loginPath, private SessionInterface $session)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ForbiddenException) {
            (new FlashService($this->session))->error('Vous devez vous connecter pour accéder à cette page');
            $this->session->set('auth.redirect', $request->getUri()->getPath());
            return new RedirectResponse($this->loginPath);
        }
    }
}
