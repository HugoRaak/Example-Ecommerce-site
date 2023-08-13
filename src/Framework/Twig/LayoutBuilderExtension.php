<?php

declare(strict_types=1);

namespace Framework\Twig;

use Framework\Router;
use PHP_CodeSniffer\Generators\HTML;
use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class LayoutBuilderExtension extends AbstractExtension
{
    public function __construct(readonly private ContainerInterface $container, readonly private Router $router)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_navbar_logo', $this->getNavbarLogo(...), ['is_safe' => ['html']]),
            new TwigFunction('get_navbar_link', $this->getNavbarLink(...), ['is_safe' => ['html']]),
            new TwigFunction('is_there_auth', $this->isThereAuth(...))
        ];
    }

    /**
     * Retrieve the logo with the right link for the layout
     */
    public function getNavbarLogo(): string
    {
        if($this->container->has('article.prefix')) 
            return <<<HTML
            <a class="navbar-brand" href="{$this->router->getUri('article.index')}">
                <img class="rotation" src="/img/logo.png" alt="Logo Agora Francia" height="70" width="70">
            </a>
            HTML;
        
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
        if($this->container->has('article.prefix')) {
            $isActiveIndex = str_contains(
                $_SERVER['REQUEST_URI'] ?? '/',
                $this->router->getUri('article.index')
            ) ? 'active' : '';
            $isActiveBrowse = str_contains(
                $_SERVER['REQUEST_URI'] ?? '/',
                $this->router->getUri('article.browse.index')
            ) ? 'active' : '';
            $navbar .= <<<HTML
                <li class="nav-item">
                    <a class="nav-link {$isActiveIndex}" 
                    href="{$this->router->getUri('article.index')}">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {$isActiveBrowse}" 
                    href="{$this->router->getUri('article.browse.index')}">Parcourir</a>
                </li>
            HTML;
        }
        if($this->container->has('contact.prefix')) {
            $isActive = str_contains(
                $_SERVER['REQUEST_URI'] ?? '/',
                $this->router->getUri('contact')
            ) ? 'active' : '';
            $navbar .= <<<HTML
                <li class="nav-item">
                    <a class="nav-link {$isActive}" 
                    href="{$this->router->getUri('contact')}">Contact</a>
                </li>
            HTML;
        }
        if($this->container->has('user.prefix')) {
            $isActive = str_contains(
                $_SERVER['REQUEST_URI'] ?? '/',
                $this->router->getUri('user.article.index')
            ) ? 'active' : '';
            $navbar .= <<<HTML
                <li class="nav-item">
                    <a class="nav-link {$isActive}" 
                    href="{$this->router->getUri('user.article.index')}">Compte</a>
                </li>
            HTML;
        }
        return <<<HTML
        <ul class="navbar-nav ml-auto">
            {$navbar}
        </ul>
        HTML;
    }

    /**
     * Check if auth module is actived
     */
    public function isThereAuth(): bool {
        return $this->container->has('auth.login');
    }
}
