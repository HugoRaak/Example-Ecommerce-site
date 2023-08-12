<?php

declare(strict_types=1);

namespace App\Contact;

use App\Contact\Actions\ContactAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Psr\Container\ContainerInterface;

final class ContactModule extends Module
{
    public const DEFINITIONS = __DIR__ . '/config.php';

    public function __construct(readonly ContainerInterface $container)
    {
        $container->get(RendererInterface::class)->addPath('contact', dirname(__DIR__) . '/Contact/views');
        $prefix = $container->get('contact.prefix');
        $container->get(\Framework\Router::class)
            ->get($prefix, ContactAction::class, 'contact')
            ->post($prefix, ContactAction::class);
    }
}
