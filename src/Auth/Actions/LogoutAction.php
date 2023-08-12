<?php declare(strict_types=1);
namespace App\Auth\Actions;

use App\Auth\DatabaseAuth;
use Framework\Actions\RouterAware;
use Framework\Response\RedirectResponse;
use Framework\Router;
use Framework\Session\FlashService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

final readonly class LogoutAction
{
    use RouterAware;

    public function __construct(
        private DatabaseAuth $auth,
        private FlashService $flash,
        private Router $router
    ) {
    }

    /**
     * logout the user and redirect him to the previous url
     *
     */
    public function __invoke(Request $request): ResponseInterface
    {
        $this->auth->logout();
        $this->flash->success('Vous êtes maintenant déconnecté');
        if (isset($request->getParsedBody()['save_uri'])) {
            return new RedirectResponse($request->getParsedBody()['save_uri']);
        }
        return $this->redirect('article.index');
    }
}
