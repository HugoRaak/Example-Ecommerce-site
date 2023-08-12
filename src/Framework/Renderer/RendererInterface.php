<?php

namespace Framework\Renderer;

interface RendererInterface
{
    /**
     * add path to change view
     * @param string|null $path=null
     *
     */
    public function addPath(string $namespace, ?string $path = null): void;

    /**
     * add global variable to all views
     *
     */
    public function addGlobal(string $key, mixed $global): void;

    /**
     * render a view
     * path can be precise with namespace
     * $this->render('@namespace/view');
     * @param mixed[] $params
     *
     */
    public function render(string $view, array $params = []): string;
}
