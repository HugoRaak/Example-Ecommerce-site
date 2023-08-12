<?php

declare(strict_types=1);

namespace Framework\Twig;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap5View;
use Twig\Extension\AbstractExtension;

final class PagerFantaExtension extends AbstractExtension
{
    public function __construct(readonly private \Framework\Router $router)
    {
    }

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('paginateExtension', $this->paginateExtension(...), ['is_safe' => ['html']])
        ];
    }

    /**
     * return button to paginate a pagerfanta object
     *
     * @param Pagerfanta<\Pagerfanta\PagerfantaInterface> $paginatedResult
     * @param mixed[] $params
     * @param mixed[] $queryArgs
     *
     */
    public function paginateExtension(
        Pagerfanta $paginatedResult,
        string $route,
        array $params = [],
        array $queryArgs = []
    ): string {
        $view = new TwitterBootstrap5View();
        return $view->render($paginatedResult, function (int $page) use ($route, $params, $queryArgs) {
            if ($page > 1) {
                $queryArgs['p'] = $page;
            }
            return $this->router->getUri($route, $params, $queryArgs);
        });
    }
}
