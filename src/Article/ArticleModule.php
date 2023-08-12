<?php declare(strict_types=1);
namespace App\Article;

use Framework\Module;
use Framework\Renderer\RendererInterface;
use Psr\Container\ContainerInterface;
use App\Article\Actions\ArticleAction;
use App\Article\Actions\ArticleCrudAction;
use App\Article\Actions\ArticleBrowseAction;
use App\Article\Actions\CategorieCrudAction;

final class ArticleModule extends Module
{
    protected const DEFINITIONS = __DIR__ . '/config.php';

    protected const MIGRATIONS = __DIR__ . '/db/migrations';

    protected const SEEDS = __DIR__ . '/db/seeds';

    public function __construct(readonly ContainerInterface $container)
    {
        $container->get(RendererInterface::class)->addPath('article', dirname(__DIR__) . '/Article/views');
        $router = $container->get(\Framework\Router::class);
        $prefix = $container->get('article.prefix');
        $router
            ->get($prefix . '/accueil', ArticleAction::class, 'article.index')
            ->get($container->get('article.show.prefix') . '/[slug:slug]-[i:id]', ArticleAction::class, 'article.show')
            ->get($prefix . '/parcourir', ArticleBrowseAction::class, 'article.browse.index')
            ->get($prefix . '/parcourir/categorie/[slug:slug]', ArticleBrowseAction::class, 'article.browse.categorie')
            ->get($prefix . '/parcourir/recherche/[slug:slug]', ArticleBrowseAction::class, 'article.browse.search')
            ->post($prefix . '/parcourir/recherche', ArticleBrowseAction::class, 'article.browse.research');

        if ($container->has('admin.prefix')) {
            $prefix = $container->get('admin.prefix');
            $router->get($prefix . '/articles', ArticleCrudAction::class, 'admin.article.index');
            $router->delete($prefix . '/articles/supprimer-[i:id]', ArticleCrudAction::class, 'admin.article.delete');
            $router->crud($prefix . '/categories', CategorieCrudAction::class, 'admin.categorie');
        }
    }
}
