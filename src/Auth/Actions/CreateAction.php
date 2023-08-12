<?php declare(strict_types=1);
namespace App\Auth\Actions;

use App\Auth\Database\Entity\User;
use App\Auth\Database\Table\RoleTable;
use App\Auth\Database\Table\UserTable;
use App\Auth\UserValidator;
use Framework\Database\Entity\Entity;
use Framework\Renderer\RendererInterface;
use Framework\Response\RedirectResponse;
use Framework\Router;
use Framework\Session\FlashService;
use Framework\Session\SessionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CreateAction
{
    public function __construct(
        readonly private RendererInterface $renderer,
        readonly private UserTable $userTable,
        readonly private SessionInterface $session,
        readonly private Router $router,
        readonly private RoleTable $roleTable
    ) {
    }

    /**
     * create a user
     *
     */
    public function __invoke(Request $request): string|RedirectResponse
    {
        $user = new User();
        $errors = [];

        if ($request->getMethod() === 'POST') {
            $params = is_array($request->getParsedBody()) ? $request->getParsedBody() : [];
            $v = new UserValidator($params, $this->userTable);

            if ($v->validate()) {
                (new FlashService($this->session))->success('Votre compte a bien été créer');
                $params['role_id'] = $this->roleTable->findBy('name', 'utilisateur')->__get('id');
                $this->userTable->insert($this->getParams($params));
                $this->session->set('auth.user', (int)$this->userTable->lastInsertId());
                $url = $this->session->get('auth.redirect') ?: $this->router->getUri('article.index');
                $this->session->delete('auth.redirect');
                return new RedirectResponse($url);
            } else {
                $errors = $v->errors();
            }
            $user = Entity::hydrate($user, $params);
        }
        return $this->renderer->render('@auth/create', ['user' => $user, 'errors' => $errors]);
    }

    /**
     * get parameters for insert
     * @param mixed[] $params
     *
     * @return mixed
     */
    private function getParams(array $params)
    {
        $params = array_filter($params, function ($key) {
            return in_array($key, ['username', 'email', 'password', 'role_id']);
        }, ARRAY_FILTER_USE_KEY);
        $params['password'] = password_hash($params['password'], PASSWORD_ARGON2ID);
        return $params;
    }
}
