<?php declare(strict_types=1);
namespace App\Article\Actions;

use Framework\Actions\CrudAction;
use Framework\Database\Table\CategorieTable;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use App\Article\CategorieValidator;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * CRUD action about categorie
 */
final class CategorieCrudAction extends CrudAction
{
    protected string $validator = CategorieValidator::class;

    protected string $viewPath = '@article/admin/categorie';

    protected string $routePrefix = 'admin.categorie';

    public function __construct(
        /** @var \Framework\Renderer\TwigRenderer $rendererInterface */
        readonly RendererInterface $rendererInterface,
        readonly Router $router,
        readonly CategorieTable $categorieTable,
        readonly FlashService $flash
    ) {
        parent::__construct($rendererInterface, $router, $categorieTable, $flash);
    }

    /**
     * get parameters from a form
     *
     * @return string[]
     */
    protected function getParams(Request $request, object $entity): array
    {
        $params = $request->getParsedBody() + $request->getUploadedFiles();
        return array_filter($params, function ($key) {
            return in_array($key, ['name', 'slug']);
        }, ARRAY_FILTER_USE_KEY);
    }
}
