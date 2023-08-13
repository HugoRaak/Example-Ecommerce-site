<?php

use App\Article\UrlArticleMiddleware;
use Framework\Helper;
use Psr\Container\ContainerInterface;

use function DI\{add, get, string};

return [
    'article.prefix' => '/article',
    'article.show.prefix' => string('{article.prefix}/afficher'),
    'admin.widgets' => add([
        get(\App\Article\ArticleWidget::class)
    ]),
    'middlewares' => add([
        fn(ContainerInterface $c) =>
        [UrlArticleMiddleware::class, [Helper::containerGetOrDefault($c, 'article.show.prefix')]]
    ])
];
