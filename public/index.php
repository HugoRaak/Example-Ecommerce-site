<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Admin\AdminModule;
use App\Article\ArticleModule;
use App\Article\UrlArticleMiddleware;
use App\Auth\AuthModule;
use App\Auth\Middleware\ForbiddenMiddleware;
use App\Auth\Middleware\LoggedInMiddleware;
use App\Auth\Middleware\OwnedMiddleware;
use App\Auth\Middleware\AdminMiddleware;
use App\Contact\ContactModule;
use App\User\UserModule;
use Dotenv\Dotenv;
use Framework\Middleware\TrailingSlashMiddleware;
use Framework\Middleware\MethodMiddleware;
use Framework\Middleware\CsrfMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Middleware\DispatcherMiddleware;
use Framework\Middleware\NotFoundMiddleware;
use Middlewares\Whoops;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$app = (new \Framework\App(dirname(__DIR__) . '/config/config.php'))
    ->addModule(AdminModule::class)
    ->addModule(ArticleModule::class)
    ->addModule(UserModule::class)
    ->addModule(AuthModule::class)
    ->addModule(ContactModule::class);

$container = $app->getContainer();
$app->pipe(Whoops::class)
    ->pipe(TrailingSlashMiddleware::class)
    ->pipe(NotFoundMiddleware::class)
    ->pipe(MethodMiddleware::class)
    ->pipe(CsrfMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(UrlArticleMiddleware::class, [$container->get('article.show.prefix')])
    ->pipe(ForbiddenMiddleware::class)
    ->pipe(LoggedInMiddleware::class, [
            $container->get('user.prefix'),
            $container->get('admin.prefix'),
            $container->get('pay.prefix')
        ])
    ->pipe(OwnedMiddleware::class, [$container->get('user.edit.prefix'), $container->get('user.delete.prefix')])
    ->pipe(AdminMiddleware::class, [$container->get('admin.prefix')])
    ->pipe(DispatcherMiddleware::class);

if (php_sapi_name() !== 'cli') {
    /** @var \Psr\Http\Message\ResponseInterface $response */
    $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
    \Http\Response\send($response);
}
