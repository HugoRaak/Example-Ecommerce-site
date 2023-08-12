<?php declare(strict_types=1);
namespace Framework\Twig;

use Twig\Extension\AbstractExtension;

final class TextExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new \Twig\TwigFilter('exerpt', [$this, 'exerpt'], ['is_safe' => ['html']])
        ];
    }

    /**
     * retrieve an exerpt of a text
     * @param string $text
     * @param int $maxLenght
     *
     * @return string
     */
    public function exerpt(?string $text, int $maxLenght = 80): string
    {
        if ($text === null) {
            return '';
        }
        if (mb_strlen($text) < $maxLenght) {
            return $text;
        }
        $lastSpace = mb_strpos($text, ' ', $maxLenght);
        if ($lastSpace === false) {
            return mb_substr($text, 0, $maxLenght+20);
        }
        return mb_substr($text, 0, $lastSpace) . '...';
    }
}
