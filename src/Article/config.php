<?php

use App\Article\ArticleWidget;

use function DI\get;
use function DI\string;

return [
    'article.prefix' => '/article',
    'article.show.prefix' => string('{article.prefix}/afficher'),
    'admin.widgets' => \DI\add([
        get(ArticleWidget::class)
    ])
];
