<?php

declare(strict_types=1);

namespace App\Auth\Middleware;

use Framework\Database\Table\ArticleTable;
use Framework\Router\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Check if the user is the owner of the article
 */
final readonly class OwnedMiddleware implements MiddlewareInterface
{
    public function __construct(private ArticleTable $articleTable)
    {
    }

    /**
     * @throws NotFoundException if the request article not belong to the user
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');
        $article = $this->articleTable->find((int)$request->getAttribute('id'));
        if ($article->__get('user_id') !== $user->__get('id')) {
            throw new NotFoundException();
        }
        return $handler->handle($request);
    }
}
