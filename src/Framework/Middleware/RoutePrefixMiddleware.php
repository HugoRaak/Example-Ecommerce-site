<?php

declare(strict_types=1);

namespace Framework\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * If a middleware is used for specific routes,
 * this middleware checks whether the URL corresponds to the route prefix
 * and determines whether to execute the middleware or not.
 */
final readonly class RoutePrefixMiddleware implements MiddlewareInterface
{
    /** @param string[] $routePrefix */
    public function __construct(
        private ContainerInterface $container,
        private array $routePrefix,
        private string $middleware
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        foreach ($this->routePrefix as $routePrefix) {
            if ($routePrefix !== null && str_starts_with($path, $routePrefix)) {
                return $this->container->get($this->middleware)->process($request, $handler);
            }
        }
        return $handler->handle($request);
    }
}
