<?php declare(strict_types=1);
namespace App\Auth\Actions;

use App\Auth\Database\Entity\User;
use App\Auth\Database\Table\RoleTable;
use App\Auth\DatabaseAuth;
use Framework\Renderer\RendererInterface;
use Framework\Response\RedirectResponse;
use Framework\Router;
use Framework\Session\FlashService;
use Framework\Session\SessionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

final class LoginAction
{
    public function __construct(
        readonly private RendererInterface $renderer,
        readonly private Router $router,
        readonly private DatabaseAuth $auth,
        readonly private SessionInterface $session,
        readonly private RoleTable $roleTable
    ) {
    }

    /**
     * login a user and redirect him to the previous url
     *
     */
    public function __invoke(Request $request): string|RedirectResponse
    {
        if ($request->getMethod() === 'POST') {
            $params = is_array($request->getParsedBody()) ? $request->getParsedBody() : [];
            if (isset($params['username']) && isset($params['password'])) {
                $user = $this->auth->login($params['username'], $params['password']);
                if ($user instanceof \App\Auth\Database\Entity\User) {
                    return new RedirectResponse($this->getRedirectUrl($user));
                }
                (new FlashService($this->session))->error('Le nom d\'utilisateur ou le mot de passe est incorrect');
            } else {
                $this->session->set('auth.redirect', $params['save_uri']);
            }
        }
        return $this->renderer->render('@auth/login');
    }

    /**
     * return the right url to redirect
     *
     */
    private function getRedirectUrl(User $user): string
    {
        if ($this->session->get('auth.redirect')) {
            $url = $this->session->get('auth.redirect');
            $this->session->delete('auth.redirect');
            return $url;
        }
        $role = $this->roleTable->findFromTable('user', $user->__get('id'));
        return $this->router->getUri(($role->__get('name') === 'admin') ? 'admin' : 'article.index');
    }
}
