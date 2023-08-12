<?php declare(strict_types=1);
namespace Framework\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class CurrentPathExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_path', [$this, 'getCurrentPath'])
        ];
    }

    /**
     * retrieve the current request uri
     * @return string
     */
    public function getCurrentPath(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
}
