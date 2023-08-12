<?php declare(strict_types=1);
namespace Framework\Response;

use GuzzleHttp\Psr7\Response;

/**
 * Redirect url with status 200
 */
final class RedirectResponse extends Response
{
    public function __construct(readonly string $url)
    {
        parent::__construct(200, ['Location' => $url]);
    }
}
