<?php

declare(strict_types=1);

namespace App\Article;

use App\Admin\AdminWidgetInterface;
use Framework\Database\Table\ArticleTable;
use Framework\Database\Table\CategorieTable;
use Framework\Renderer\RendererInterface;

final readonly class ArticleWidget implements AdminWidgetInterface
{
    public function __construct(
        private RendererInterface $renderer,
        private ArticleTable $articleTable,
        private CategorieTable $categorieTable
    ) {
    }

    /**
     * display widget on the admin dashboard
     */
    public function render(): string
    {
        $countArticle = $this->articleTable->count();
        $countCategorie = $this->categorieTable->count();
        return $this->renderer->render(
            '@article/admin/widget',
            ['countArticle' => $countArticle, 'countCategorie' => $countCategorie]
        );
    }

    /**
     * display the link in the admin navbar
     */
    public function renderMenu(): string
    {
        return $this->renderer->render('@article/admin/menu');
    }
}
