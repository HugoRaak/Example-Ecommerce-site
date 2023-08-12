<?php

use App\Auth\Middleware\ForbiddenMiddleware;
use App\Auth\UserWidget;

use function DI\autowire;
use function DI\get;
use function DI\add;

return [
    'auth.login' => '/login',
    'pay.prefix' => '/acheter',
    \Framework\Auth\AuthInterface::class => autowire(\App\Auth\DatabaseAuth::class),
    ForbiddenMiddleware::class => autowire()->constructorParameter('loginPath', get('auth.login')),
    'twig.extensions' => add([
        get(\App\Auth\AuthTwigExtension::class)
    ]),
    'admin.widgets' => add([
        get(UserWidget::class)
    ])
];
