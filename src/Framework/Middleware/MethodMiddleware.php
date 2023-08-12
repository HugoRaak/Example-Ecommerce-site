<?php declare(strict_types=1);
namespace Framework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * resend the request with the method PUT or DELETE
 * if form contains a hidden input _method
 */
final class MethodMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $parsedBody = is_array($request->getParsedBody()) ? $request->getParsedBody() : [];
        if (array_key_exists('_method', $parsedBody) &&
            in_array($parsedBody['_method'], ['DELETE', 'PUT'])
        ) {
            $request = $request->withMethod($parsedBody['_method']);
        }
        return $handler->handle($request);
    }
}
