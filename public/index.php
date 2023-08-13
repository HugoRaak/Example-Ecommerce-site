<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Admin\AdminModule;
use App\Article\ArticleModule;
use App\Auth\AuthModule;
use App\Contact\ContactModule;
use App\User\UserModule;
use Dotenv\Dotenv;
use Framework\Middleware\DispatcherMiddleware;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$app = (new \Framework\App(dirname(__DIR__) . '/config/config.php'))
    ->addModule(AdminModule::class)
    ->addModule(ArticleModule::class)
    ->addModule(UserModule::class) //this module needs AuthModule
    ->addModule(AuthModule::class)
    ->addModule(ContactModule::class);

$container = $app->getContainer();
foreach ($container->get('middlewares') as $middleware) {
    if (is_array($middleware)) {
        if (count($middleware) > 2) {
            foreach ($middleware as $m) {
                is_array($m) ? $app->pipe($m[0], $m[1]) : $app->pipe($m);
            }
        } else {
            $app->pipe($middleware[0], $middleware[1]);
        }
    } else {
        $app->pipe($middleware);
    }
}
$app->pipe(DispatcherMiddleware::class);

if (php_sapi_name() !== 'cli') {
    /** @var \Psr\Http\Message\ResponseInterface $response */
    $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
    \Http\Response\send($response);
}
