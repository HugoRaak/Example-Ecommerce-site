<?php declare(strict_types=1);
namespace Framework\Renderer;

use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Retrieve an instance of twig renderer
 */
final class TwigRendererFactory
{
    public function __invoke(ContainerInterface $container): TwigRenderer
    {
        $debug = $container->get('env') !== 'production';
        $loader = new FilesystemLoader($container->get('views.path'));
        $twig = new Environment($loader, [
            'debug' => $debug,
            'cache' => $debug ? false : dirname(__DIR__, 3) . '/tmp/views',
            'auto_reload' => $debug
        ]);
        if ($container->has('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($extension);
            }
        }
        return new TwigRenderer($twig);
    }
}
