<?php declare(strict_types=1);
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
final class RoutePrefixMiddleware implements MiddlewareInterface
{
    /** @param string[] $routePrefix */
    public function __construct(
        readonly private ContainerInterface $container,
        readonly private array $routePrefix,
        readonly private string $middleware
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        foreach ($this->routePrefix as $routePrefix) {
            if (strpos($path, $routePrefix) === 0) {
                return $this->container->get($this->middleware)->process($request, $handler);
            }
        }
        return $handler->handle($request);
    }
}
