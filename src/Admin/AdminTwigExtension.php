<?php

declare(strict_types=1);

namespace App\Admin;

use Framework\Renderer\RendererInterface;
use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;

final class AdminTwigExtension extends AbstractExtension
{
    /**
     * @param  AdminWidgetInterface[] $widgets
     */
    public function __construct(readonly private array $widgets, readonly private ContainerInterface $container)
    {
    }

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('admin_menu', $this->renderMenu(...), ['is_safe' => ['html']]),
            new \Twig\TwigFunction('logout_button', $this->getLogoutButton(...), ['is_safe' => ['html']])
        ];
    }

    /**
     * render view the navbar link of widgets
     */
    public function renderMenu(): string
    {
        return array_reduce(
            $this->widgets,
            fn(string $html, AdminWidgetInterface $widget) => $html . $widget->renderMenu(),
            ''
        );
    }

    /**
     * if auth module is actived render logout button
     */
    public function getLogoutButton(): string
    {
        if ($this->container->has('auth.login')) {
            return $this->container->get(RendererInterface::class)->render('@auth/admin/logout_button');
        }
        return '';
    }
}
