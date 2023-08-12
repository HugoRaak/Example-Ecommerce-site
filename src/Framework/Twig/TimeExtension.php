<?php declare(strict_types=1);
namespace Framework\Twig;

use Twig\Extension\AbstractExtension;

final class TimeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new \Twig\TwigFilter('ago', $this->ago(...), ['is_safe' => ['html']])
        ];
    }

    /**
     * generate a tag html to get the date with timeago.js
     *
     *
     */
    public function ago(\DateTime $date, string $format = "Y-m-d H:i"): string
    {
        return '<time class="timeago" datetime="' . $date->format(\DateTime::ATOM) . '">' .
        $date->format($format) .
        '</time>';
    }
}
