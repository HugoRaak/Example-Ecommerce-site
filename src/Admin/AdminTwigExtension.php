<?php declare(strict_types=1);
namespace App\Admin;

use Twig\Extension\AbstractExtension;

final class AdminTwigExtension extends AbstractExtension
{
    /**
     * @param  AdminWidgetInterface[] $widgets
     */
    public function __construct(readonly private array $widgets)
    {
    }

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('admin_menu', [$this, 'renderMenu'], ['is_safe' => ['html']])
        ];
    }

    /**
     * render view the navbar link of widgets
     * @return string
     */
    public function renderMenu(): string
    {
        return array_reduce($this->widgets, function (string $html, AdminWidgetInterface $widget) {
            return $html . $widget->renderMenu();
        }, '');
    }
}
