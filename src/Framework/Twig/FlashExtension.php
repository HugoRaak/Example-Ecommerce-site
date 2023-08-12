<?php declare(strict_types=1);
namespace Framework\Twig;

use Framework\Session\FlashService;
use Twig\Extension\AbstractExtension;

final class FlashExtension extends AbstractExtension
{
    public function __construct(readonly private FlashService $flash)
    {
    }

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('flash', [$this, 'getFlash'])
        ];
    }

    /**
     * retrieve the flash message
     * @param string $type
     *
     * @return string|null
     */
    public function getFlash(string $type): ?string
    {
        return $this->flash->get($type);
    }
}
