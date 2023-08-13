<?php

declare(strict_types=1);

namespace App\Article\Actions;

use Framework\Database\Table\ArticleTable;
use Framework\Actions\RouterAware;
use Framework\Database\Table\CategorieTable;
use Framework\Helper;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;

final readonly class ArticleBrowseAction
{
    use RouterAware;

    public function __construct(
        private RendererInterface $renderer,
        private Router $router,
        private ArticleTable $articleTable,
        private CategorieTable $categorieTable
    ) {
    }

    public function __invoke(Request $request): string|ResponseInterface
    {
        if (str_contains((string)$request->getUri(), 'recherche')) {
            return $this->search($request);
        } elseif ($request->getAttribute('slug')) {
            return $this->categorie($request);
        }
        return $this->index($request);
    }


    /**
     * display all the articles
     *
     */
    private function index(Request $request): string
    {
        $params =  $request->getQueryParams();
        $articles = $this->articleTable->findPaginated(12, (int)($params['p'] ?? 1));
        return $this->renderer->render('@article/browse', $this->getRenderParams(['articles' => $articles], '.index'));
    }

    /**
     * display all the articles from a categorie
     *
     */
    private function categorie(Request $request): string
    {
        $params =  $request->getQueryParams();
        $slug = $request->getAttribute('slug');
        $categorie = $this->categorieTable->findBy('slug', $slug);
        $articles = $this->articleTable->findPaginatedFromCategorie(
            12,
            (int)($params['p'] ?? 1),
            $categorie->__get('id')
        );
        return $this->renderer->render(
            '@article/browse',
            $this->getRenderParams(['articles' => $articles, 'slug' => $slug], '.categorie')
        );
    }

    /**
     * display all the articles from a research
     *
     * @return string
     */
    private function search(Request $request): string|ResponseInterface
    {
        if ($request->getMethod() === 'POST') {
            $search = $_POST['search'] ?? '';
            if ($search === '') {
                return $this->redirect('article.browse.index');
            }
            return $this->redirect('article.browse.search', ['slug' => Helper::createSlug($search, 200)]);
        }
        $params = $request->getQueryParams();
        $slug = $request->getAttribute('slug');
        $search = str_replace('-', ' ', (string) $slug);
        $articles = $this->articleTable->findPaginatedArray(
            12,
            (int)($params['p'] ?? 1),
            $this->articleTable->getArticlesFromSearch($search)
        );
        return $this->renderer->render(
            '@article/browse',
            $this->getRenderParams(['articles' => $articles, 'slug' => $slug], '.search')
        );
    }

    /**
     * get params for the render view
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    private function getRenderParams(array $params, string $routeSuffix): array
    {
        $categories = $this->categorieTable->findAll('name DESC');
        return array_merge($params, ['routeSuffix' => $routeSuffix, 'categories' => $categories]);
    }
}
