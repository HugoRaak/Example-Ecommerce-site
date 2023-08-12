<?php
namespace Framework\Renderer;

interface RendererInterface
{
    /**
     * add path to change view
     * @param string $namespace
     * @param string|null $path=null
     *
     * @return void
     */
    public function addPath(string $namespace, ?string $path = null): void;

    /**
     * add global variable to all views
     * @param string $key
     * @param mixed $global
     *
     * @return void
     */
    public function addGlobal(string $key, mixed $global): void;

    /**
     * render a view
     * path can be precise with namespace
     * $this->render('@namespace/view');
     * @param string $view
     * @param mixed[] $params
     *
     * @return string
     */
    public function render(string $view, array $params = []): string;
}
