<?php

declare(strict_types=1);

namespace Framework;

use AltoRouter;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Router\Route;

/**
 * Class Router
 * register and match routes
 */
class Router
{
    readonly private AltoRouter $router;

    public function __construct()
    {
        $this->router = new AltoRouter();
    }

    /**
     * add route with method GET
     * @param string|callable $callback
     *
     */
    public function get(string $url, string|callable $callback, string $name): self
    {
        $this->router->map('GET', $url, $callback, $name);
        return $this;
    }

    /**
     * add route with method POST
     * @param string|callable $callback
     * @param string $name
     *
     */
    public function post(string $url, string|callable $callback, ?string $name = null): self
    {
        $this->router->map('POST', $url, $callback, $name);
        return $this;
    }

    /**
     * add route with method DELETE
     * @param string|callable $callback
     * @param string|null $name
     *
     */
    public function delete(string $url, string|callable $callback, ?string $name = null): self
    {
        $this->router->map('DELETE', $url, $callback, $name);
        return $this;
    }

    /**
     * try to match the request uri with a route
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $result = $this->router->match($request->getUri()->getPath(), $request->getMethod());
        if ($result !== false && is_array($result)) {
            return new Route($result['name'], $result['target'], $result['params']);
        }
        return null;
    }

    /**
     * retrieve the uri correspond to a route
     * @param mixed[] $params
     * @param mixed[] $queryArgs
     *
     */
    public function getUri(string $name, array $params = [], array $queryArgs = []): string
    {
        $uri = $this->router->generate($name, $params);
        if ($queryArgs !== []) {
            return $uri . '?' . http_build_query($queryArgs);
        }
        return $uri;
    }

    /**
     * add match type for the route creation
     *
     */
    public function addMatchTypes(string $key, string $regex): void
    {
        $this->router->addMatchTypes([$key => $regex]);
    }

    /**
     * generate routes of the CRUD
     *
     *
     */
    public function crud(string $prefix, string $action, string $prefixPath): void
    {
        $this
            ->get($prefix, $action, $prefixPath . '.index')
            ->get($prefix . '/modifier-[i:id]', $action, $prefixPath . '.edit')
            ->post($prefix . '/modifier-[i:id]', $action)
            ->get($prefix . '/nouveau', $action, $prefixPath . '.add')
            ->post($prefix . '/nouveau', $action)
            ->delete($prefix . '/supprimer-[i:id]', $action, $prefixPath . '.delete');
    }
}
