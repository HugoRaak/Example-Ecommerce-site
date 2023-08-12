<?php

declare(strict_types=1);

namespace App\User;

use App\User\Actions\UserCrudAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Psr\Container\ContainerInterface;

final class UserModule extends Module
{
    protected const DEFINITIONS = __DIR__ . '/config.php';

    public function __construct(readonly ContainerInterface $container)
    {
        $container->get(RendererInterface::class)->addPath('user', dirname(__DIR__) . '/User/views');
        $router = $container->get(\Framework\Router::class);
        $router->crud($container->get('user.prefix'), UserCrudAction::class, 'user.article');
    }
}
