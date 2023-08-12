<?php

declare(strict_types=1);

namespace App\Article\Actions;

use Framework\Actions\CrudAction;
use Framework\Database\Table\ArticleTable;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;

/**
 * CRUD action about article for admin
 */
final class ArticleCrudAction extends CrudAction
{
    protected string $viewPath = '@article/admin/article';

    protected string $routePrefix = 'admin.article';

    public function __construct(
        /** @var \Framework\Renderer\TwigRenderer $rendererInterface */
        readonly RendererInterface $rendererInterface,
        readonly Router $router,
        readonly ArticleTable $articleTable,
        readonly FlashService $flash
    ) {
        parent::__construct($rendererInterface, $router, $articleTable, $flash);
    }
}
