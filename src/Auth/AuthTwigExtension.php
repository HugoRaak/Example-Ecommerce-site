<?php declare(strict_types=1);
namespace App\Auth;

use Framework\Auth\AuthInterface;
use Twig\Extension\AbstractExtension;

final class AuthTwigExtension extends AbstractExtension
{
    /**
     * @param DatabaseAuth $auth
     */
    public function __construct(readonly private AuthInterface $auth)
    {
    }

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('current_user', $this->auth->getUser(...)),
            new \Twig\TwigFunction('is_admin', fn() => $this->auth->isAdmin()),
        ];
    }
}
