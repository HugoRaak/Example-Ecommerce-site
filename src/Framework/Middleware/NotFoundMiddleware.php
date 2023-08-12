<?php

declare(strict_types=1);

namespace Framework\Middleware;

use Framework\Database\Table\NoRecordException;
use Framework\Router\NotFoundException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * catch the notFoundError or noRecordFound and redirect to a 404 error page
 */
final class NotFoundMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (NotFoundException | NoRecordException) {
            return new Response(404, [], '<h1>Erreur 404</h1>');
        }
    }
}
