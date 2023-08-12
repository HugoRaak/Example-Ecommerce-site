<?php

declare(strict_types=1);

namespace App\Article;

use Framework\Actions\RouterAware;
use Framework\Database\Table\ArticleTable;
use Framework\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Verify url to display an article
 */
final readonly class UrlArticleMiddleware implements MiddlewareInterface
{
    use RouterAware;

    public function __construct(private ArticleTable $articleTable, private Router $router)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $article = $this->articleTable->find((int)$request->getAttribute('id'));
        if ($article->__get('slug') !== $request->getAttribute('slug')) {
            return $this->redirect('article.show', ['slug' => $article->__get('slug') , 'id' => $article->__get('id')]);
        }
        return $handler->handle($request);
    }
}
