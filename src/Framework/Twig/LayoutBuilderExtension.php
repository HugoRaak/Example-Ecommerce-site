<?php

declare(strict_types=1);

namespace Framework\Twig;

use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class LayoutBuilderExtension extends AbstractExtension
{
    public function __construct(
        readonly private ContainerInterface $container,
        readonly private Router $router,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_navbar_logo', $this->getNavbarLogo(...), ['is_safe' => ['html']]),
            new TwigFunction('get_navbar_link', $this->getNavbarLink(...), ['is_safe' => ['html']]),
            new TwigFunction('get_login_button', $this->getLoginButton(...), ['is_safe' => ['html']])
        ];
    }

    /**
     * Retrieve the logo with the right link for the layout
     */
    public function getNavbarLogo(): string
    {
        if ($this->container->has('article.prefix')) {
            return <<<HTML
            <a class="navbar-brand" href="{$this->router->getUri('article.index')}">
                <img class="rotation" src="/img/logo.png" alt="Logo Agora Francia" height="70" width="70">
            </a>
            HTML;
        }

        return <<<HTML
        <img class="rotation" src="/img/logo.png" alt="Logo Agora Francia" height="70" width="70">
        HTML;
    }

    /**
     * retrieve the navbar for the layout
     */
    public function getNavbarLink(): string
    {
        $navbar = '';
        if ($this->container->has('article.prefix')) {
            $navbar .= $this->container->get(RendererInterface::class)->render('@article/navbar_link');
        }
        if ($this->container->has('contact.prefix')) {
            $navbar .= $this->container->get(RendererInterface::class)->render('@contact/navbar_link');
        }
        if ($this->container->has('user.prefix') && $this->container->has('auth.login')) {
            $navbar .= $this->container->get(RendererInterface::class)->render('@user/navbar_link');
        }
        return $navbar;
    }

    /**
     * if auth module is actived render login button
     */
    public function getLoginButton(): string
    {
        if ($this->container->has('auth.login')) {
            return $this->container->get(RendererInterface::class)->render('@auth/login_button');
        }
        return '';
    }
}
