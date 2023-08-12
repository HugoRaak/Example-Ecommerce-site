<?php declare(strict_types=1);
namespace Framework\Router;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RouterTwigExtension extends AbstractExtension
{
    public function __construct(readonly private \Framework\Router $router)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pathFor', [$this, 'pathFor']),
            new TwigFunction('is_subpath', [$this, 'isSubPath'])
        ];
    }

    /**
     * return the uri correspond to the route $name
     * @param mixed[] $params
     *
     */
    public function pathFor(string $name, array $params = []): string
    {
        return $this->router->getUri($name, $params);
    }

    /**
     * return true if the path is a sub path of the request uri
     *
     */
    public function isSubPath(string $path): bool
    {
        return strpos($_SERVER['REQUEST_URI'] ?? '/', $this->router->getUri($path)) !== false;
    }
}
