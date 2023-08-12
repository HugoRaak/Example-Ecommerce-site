<?php declare(strict_types=1);
namespace App\Admin;

use Framework\Renderer\RendererInterface;

final readonly class DashboardAction
{
    /**
     * @param  AdminWidgetInterface[] $widgets
     */
    public function __construct(private RendererInterface $renderer, private array $widgets)
    {
    }

    /**
     * render the admin dashboard
     */
    public function __invoke(): string
    {
        $widgets = array_reduce(
            $this->widgets,
            fn(string $html, AdminWidgetInterface $widget) => $html . $widget->render(),
            ''
        );
        return $this->renderer->render('@admin/dashboard', ['widgets' => $widgets]);
    }
}
