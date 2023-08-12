<?php declare(strict_types=1);
namespace App\Auth\Actions;

use App\Auth\Database\Table\RoleTable;
use App\Auth\Database\Table\UserTable;
use Framework\Actions\RouterAware;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;

final class AdminUserAction
{
    use RouterAware;

    public function __construct(
        readonly private RendererInterface $renderer,
        readonly private Router $router,
        readonly private UserTable $userTable,
        readonly private FlashService $flash,
        readonly private RoleTable $roleTable
    ) {
    }

    public function __invoke(Request $request): string|ResponseInterface
    {
        if ($request->getMethod() === 'DELETE') {
            return $this->delete($request);
        } elseif ($request->getAttribute('id')) {
            return $this->addRole($request);
        }
        return $this->index($request);
    }

    /**
     * display all users
     *
     */
    private function index(Request $request): string
    {
        $users = $this->userTable->findPaginated(12, $request->getQueryParams()['p'] ?? 1);
        $roles = $this->roleTable->findList();
        return $this->renderer->render('@auth/admin/index', compact('users', 'roles'));
    }


    /**
     * delete a user
     *
     */
    private function delete(Request $request): ResponseInterface
    {
        $this->userTable->delete((int)$request->getAttribute('id'));
        $this->flash->success('L\'utilisateur a été supprimer avec succès');
        return $this->redirect('admin.user.index');
    }

    /**
     * add a role to user
     *
     */
    private function addRole(Request $request): ResponseInterface
    {
        $params = is_array($request->getParsedBody()) ? $request->getParsedBody() : [];
        /** @var int $role_id */
        $role_id = array_key_exists('role_id', $params) ? (int)$params['role_id'] : 0;
        if (!$role_id) {
            return $this->redirect('admin.user.index');
        } elseif ($this->roleTable->exists('id', $role_id)) {
            $this->userTable->update(compact('role_id'), (int)$request->getAttribute('id'));
            $this->flash->success('Le role a bien été modifié');
            return $this->redirect('admin.user.index');
        }
        $this->flash->error('Le role n\'existe pas');
        return $this->redirect('admin.user.index');
    }
}
