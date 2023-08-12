<?php declare(strict_types=1);
namespace App\Auth\Middleware;

use Framework\Auth\ForbiddenException;
use Framework\Auth\AuthInterface;
use Framework\Router\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Check if a user is connected
 */
final class LoggedInMiddleware implements MiddlewareInterface
{
    public function __construct(readonly private AuthInterface $auth)
    {
    }

    /**
     * @throws ForbiddenException if there is no user connected
     * @throws NotFoundException if the request require admin role
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->auth->getUser();
        if (!$user instanceof \App\Auth\Database\Entity\User) {
            if (strpos($request->getUri()->getPath(), '/admin') !== false) {
                throw new NotFoundException();
            }
            throw new ForbiddenException();
        }
        return $handler->handle($request->withAttribute('user', $user));
    }
}
