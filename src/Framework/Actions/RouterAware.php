<?php declare(strict_types=1);
namespace Framework\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Add router-related methods
 *
 * @package Framework\Actions
 */
trait RouterAware
{
    /**
     * return a response of redirection
     *
     * @param string $path
     * @param mixed[] $params
     * @param mixed[] $queryArgs
     *
     * @return ResponseInterface
     */
    public function redirect(string $path, array $params = [], array $queryArgs = []): ResponseInterface
    {
        $redirectUri = $this->router->getUri($path, $params, $queryArgs);
        return (new Response())
        ->withStatus(301)
        ->withHeader('Location', $redirectUri);
    }
}
