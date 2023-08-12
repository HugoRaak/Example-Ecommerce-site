<?php declare(strict_types=1);
namespace Framework\Renderer;

final class TwigRenderer implements RendererInterface
{
    public function __construct(readonly private \Twig\Environment $twig)
    {
    }

    /**
     * add path to change view
     * @param string|null $path
     *
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        /** @var \Twig\Loader\FilesystemLoader $loader */
        $loader = $this->twig->getLoader();
        if (is_string($path)) {
            $loader->addPath($path, $namespace);
        }
    }

    /**
     * add global variable to all views
     *
     */
    public function addGlobal(string $key, mixed $value): void
    {
        $this->twig->addGlobal($key, $value);
    }

    /**
     * get the twig environment
     */
    public function getTwig(): \Twig\Environment
    {
        return $this->twig;
    }

    /**
     * render a view
     * path can be precise with namespace
     * $this->render('@namespace/view');
     * @param mixed[] $params
     *
     */
    public function render(string $view, array $params = []): string
    {
        return $this->twig->render($view . '.twig', $params);
    }
}
