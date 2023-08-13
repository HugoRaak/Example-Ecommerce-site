<?php

use Framework\Renderer\{TwigRendererFactory, RendererInterface};
use Framework\Session\{SessionInterface, PHPSession};
use Framework\Twig\{
    CsrfExtension,
    FormExtension,
    PagerFantaExtension,
    TextExtension,
    TimeExtension,
    FlashExtension,
    CurrentPathExtension,
    LayoutBuilderExtension
};
use ParagonIE\AntiCSRF\AntiCSRF;

use function DI\{autowire, factory, get};

return [
    'env' => \DI\env('ENV', 'production'),
    'database.username' => 'root',
    'database.password' => 'root',
    'database.host' => 'localhost',
    'database.name' => 'agora-francia',
    'views.path' => dirname(__DIR__) . '/views',
    'path.save.prefix' => '/helper/path/save',
    'twig.extensions' => [
        get(\Framework\Router\RouterTwigExtension::class),
        get(\Twig\Extension\DebugExtension::class),
        get(PagerFantaExtension::class),
        get(TextExtension::class),
        get(TimeExtension::class),
        get(FormExtension::class),
        get(FlashExtension::class),
        get(CsrfExtension::class),
        get(CurrentPathExtension::class),
        get(LayoutBuilderExtension::class)
    ],
    SessionInterface::class => autowire(PHPSession::class),
    AntiCSRF::class => function () {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return new AntiCSRF();
    },
    \Framework\Router::class => autowire(),
    RendererInterface::class => factory(TwigRendererFactory::class),
    \PDO::class => fn(\Psr\Container\ContainerInterface $c) => new \PDO('mysql:dbname=' . $c->get('database.name') . ';host=' . $c->get('database.host'),
                $c->get('database.username'),
                $c->get('database.password'),
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            )
];