<?php declare(strict_types=1);
namespace App\Admin;

use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRenderer;
use Framework\Router;

final class AdminModule extends Module
{
    const DEFINITIONS = __DIR__ . '/config.php';

    public function __construct(
        readonly RendererInterface $renderer,
        readonly Router $router,
        readonly AdminTwigExtension $adminTwigExtension,
        readonly string $prefix
    ) {
        $renderer->addPath('admin', __DIR__ . '/views');
        $router->get($prefix, DashboardAction::class, 'admin');
        if ($renderer instanceof TwigRenderer) {
            $renderer->getTwig()->addExtension($adminTwigExtension);
        }
    }
}
