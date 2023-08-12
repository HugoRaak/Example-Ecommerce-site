<?php declare(strict_types=1);
namespace App\Admin;

use Framework\Renderer\RendererInterface;

final class DashboardAction
{
    /**
     * @param  AdminWidgetInterface[] $widgets
     */
    public function __construct(readonly private RendererInterface $renderer, readonly private array $widgets)
    {
    }

    /**
     * render the admin dashboard
     */
    public function __invoke(): string
    {
        $widgets = array_reduce($this->widgets, function (string $html, AdminWidgetInterface $widget) {
            return $html . $widget->render();
        }, '');
        return $this->renderer->render('@admin/dashboard', ['widgets' => $widgets]);
    }
}
