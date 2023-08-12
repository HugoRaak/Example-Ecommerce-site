<?php declare(strict_types=1);
namespace Framework\Actions;

use Framework\Database\Entity\Entity;
use Framework\Database\Table\Table;
use Framework\Renderer\TwigRenderer;
use Framework\Router;
use Framework\Session\FlashService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class CrudAction
{
    /**
     * @var class-string<\Framework\Validator>
     */
    protected string $validator;

    /**
     * @var string[]
     */
    protected array $messagesFlash = [
        'create' => 'L\'élément a bien été créer',
        'edit'   => 'L\'élément a bien été modifié',
        'delete' => 'L\'élément a bien été supprimé'
    ];

    protected string $viewPath;

    protected string $routePrefix;

    use RouterAware;

    public function __construct(
        readonly protected TwigRenderer $renderer,
        readonly private Router $router,
        readonly protected Table $table,
        readonly private FlashService $flash
    ) {
    }

    public function __invoke(Request $request): string|ResponseInterface
    {
        if ($request->getMethod() === 'DELETE') {
            return $this->delete($request);
        } elseif ($request->getAttribute('id')) {
            return $this->edit($request);
        } elseif (strpos((string)$request->getUri(), 'nouveau') !== false) {
            return $this->create($request);
        }
        return $this->index($request);
    }

    /**
     * display all items
     *
     */
    protected function index(Request $request): string
    {
        $params =  $request->getQueryParams();
        $items = $this->table->findPaginated(12, (int)($params['p'] ?? 1));
        return $this->renderer->render($this->viewPath . '/index', compact('items'));
    }

    /**
     * edit an item
     *
     */
    protected function edit(Request $request): string|ResponseInterface
    {
        $id = (int)$request->getAttribute('id');
        $item = $this->table->find($id);
        $errors = [];

        if ($request->getMethod() === 'POST') {
            $v = $this->getValidator($request, $id);

            if ($v->validate()) {
                $this->flash->success($this->messagesFlash['edit']);
                $this->table->update($this->getParams($request, $item), $item->__get('id'));
                return $this->redirect($this->routePrefix . '.index');
            } else {
                $errors = $v->errors();
            }
            $item = Entity::hydrate($item, is_array($request->getParsedBody()) ? $request->getParsedBody() : []);
        }
        return $this->renderer->render($this->viewPath . '/edit', $this->formParams(compact('item', 'errors')));
    }

    /**
     * create an item
     *
     */
    protected function create(Request $request): string|ResponseInterface
    {
        $entity = $this->table->getEntity();
        $item = new $entity();
        $errors = [];

        if ($request->getMethod() === 'POST') {
            $v = $this->getValidator($request);

            if ($v->validate()) {
                $this->flash->success($this->messagesFlash['create']);
                $this->table->insert($this->getParams($request, $item));
                return $this->redirect($this->routePrefix . '.index');
            } else {
                $errors = $v->errors();
            }
            $item = Entity::hydrate($item, is_array($request->getParsedBody()) ? $request->getParsedBody() : []);
        }
        return $this->renderer->render($this->viewPath . '/create', $this->formParams(compact('item', 'errors')));
    }

    /**
     * delete an item
     *
     */
    protected function delete(Request $request): ResponseInterface
    {
        $this->table->delete((int)$request->getAttribute('id'));
        $this->flash->success($this->messagesFlash['delete']);
        return $this->redirect($this->routePrefix . '.index');
    }

    /**
     * get parameters from a form
     *
     *
     * @return mixed[]
     */
    protected function getParams(Request $request, object $entity): array
    {
        $params = $request->getParsedBody() + $request->getUploadedFiles();
        return array_filter($params, function ($key) {
            return in_array($key, []);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Retrieve parameters for the form
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    protected function formParams(array $params): array
    {
        return $params;
    }

    /**
     * Retrieve the validator
     * @param int|null $id
     *
     */
    protected function getValidator(Request $request, ?int $id = null): \Framework\Validator
    {
        return new $this->validator($request, $this->table, $id);
    }
}
