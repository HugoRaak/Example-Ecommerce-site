<?php

use App\Auth\Middleware\AdminMiddleware;
use App\Auth\Middleware\ForbiddenMiddleware;
use App\Auth\Middleware\LoggedInMiddleware;
use App\Auth\Middleware\OwnedMiddleware;
use App\Auth\UserWidget;
use Framework\Helper;
use Psr\Container\ContainerInterface;

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
    ]),
    'middlewares' => add([(fn(ContainerInterface $c) => [
            ForbiddenMiddleware::class,
            [LoggedInMiddleware::class, [
                Helper::containerGetOrDefault($c, 'user.prefix'),
                Helper::containerGetOrDefault($c, 'admin.prefix'),
                Helper::containerGetOrDefault($c, 'pay.prefix')
            ]],
            [OwnedMiddleware::class, [
                Helper::containerGetOrDefault($c, 'user.edit.prefix'),
                Helper::containerGetOrDefault($c, 'user.delete.prefix')
            ]],
            [AdminMiddleware::class, [Helper::containerGetOrDefault($c, 'admin.prefix')]]
        ])])
];
