<?php declare(strict_types=1);
namespace Framework\Middleware;

use Framework\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * match the request uri with a route
 */
final class RouterMiddleware implements MiddlewareInterface
{
    public function __construct(readonly private Router $router)
    {
    }

    /**
     * @throws \Framework\Router\NotFoundException if there is no route corresponding to the request uri
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->router->match($request);
        if ($route === null) {
            throw new \Framework\Router\NotFoundException();
        }
        $params = $route->getParams();
        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);
        $request = $request->withAttribute(get_class($route), $route);
        return $handler->handle($request);
    }
}
