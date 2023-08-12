<?php

declare(strict_types=1);

namespace Framework\Twig;

use ParagonIE\AntiCSRF\AntiCSRF;
use Twig\Extension\AbstractExtension;

final class CsrfExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('form_token', $this->formToken(...), ['is_safe' => ['html']])
        ];
    }

    /**
     * generate a hidden input with information on token for the anti-csrf
     *
     */
    public function formToken(string $lock_to = ''): string
    {
        static $csrf;
        if ($csrf === null) {
            $csrf = new AntiCSRF();
        }
        return $csrf->insertToken($lock_to, false);
    }
}
