<?php declare(strict_types=1);
namespace Framework\Middleware;

use GuzzleHttp\Psr7\Response;
use ParagonIE\AntiCSRF\AntiCSRF;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Check if the form token is correct
 */
final class CsrfMiddleware implements MiddlewareInterface
{
    public function __construct(readonly private AntiCSRF $csrf)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE']) && !$this->csrf->validateRequest()) {
            return new Response(403, [], '<h1>CSRF token is invalid</h1>');
        }
        return $handler->handle($request);
    }
}
