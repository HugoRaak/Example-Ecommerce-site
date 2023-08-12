<?php declare(strict_types=1);
namespace App\User\Actions;

use App\User\UserUpload;
use Framework\Actions\CrudAction;
use Framework\Database\Entity\Article;
use Framework\Database\Table\ArticleTable;
use Framework\Database\Table\CategorieTable;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use App\User\ArticleValidator;
use Framework\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ServerRequestInterface;

final class UserCrudAction extends CrudAction
{
    protected string $validator = ArticleValidator::class;

    protected string $viewPath = '@user';

    protected string $routePrefix = 'user.article';

    public function __construct(
        /** @var \Framework\Renderer\TwigRenderer $rendererInterface */
        readonly RendererInterface $rendererInterface,
        readonly Router $router,
        readonly ArticleTable $articleTable,
        readonly FlashService $flash,
        readonly private CategorieTable $categorieTable,
        readonly private UserUpload $userUpload,
        readonly private SessionInterface $session
    ) {
        parent::__construct($rendererInterface, $router, $articleTable, $flash);
    }

    /**
     * display all the articles of a user
     * @param Request $request
     *
     * @return string
     */
    protected function index(Request $request): string
    {
        $items = $this->table->findPaginatedArray(
            12,
            $request->getQueryParams()['p'] ?? 1,
            $this->table->findArticleFromUser($request->getAttribute('user')->__get('id'), 'created_at DESC')
        );
        return $this->renderer->render($this->viewPath . '/index', compact('items'));
    }

    /**
     * delete an article of a user
     * @param Request $request
     *
     * @return ResponseInterface
     */
    protected function delete(Request $request): ResponseInterface
    {
        /** @var Article $article */
        $article = $this->table->find((int)$request->getAttribute('id'));
        $this->deleteImages($article);
        return parent::delete($request);
    }

    /**
     * get parameters from a form
     * @param Request $request
     * @param Article $entity
     *
     * @return mixed[]
     */
    protected function getParams(Request $request, object $entity): array
    {
        $params = $request->getParsedBody() + $request->getUploadedFiles();
        $params['images'] = $this->uploadImages($params['images'], $entity);

        $params = array_filter($params, function ($key) {
            return in_array($key, ['name', 'slug', 'images','price', 'description', 'categorie_id']);
        }, ARRAY_FILTER_USE_KEY);
        $params['price'] = ($params['price'] === 'null') ? null : (float)$params['price'];
        $params['categorie_id'] = ($params['categorie_id'] === 'null') ? null : (int)$params['categorie_id'];
        $params['updated_at'] = date('Y-m-d H:i');
        if ($request->getAttribute('id') === null) {
            $params['created_at'] = date('Y-m-d H:i');
            $params['user_id'] = $this->session->get('auth.user');
        }
        return $params;
    }

    /**
     * Retrieve parameters for a form
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    protected function formParams(array $params): array
    {
        $params['categories'] = $this->categorieTable->findList();
        $params['images'] = $params['item']->showImages(false);
        return $params;
    }

    /**
     * Retrieve the validator for the CRUD object
     * @param ServerRequestInterface $request
     * @param int|null $id
     *
     * @return \Framework\Validator
     */
    protected function getValidator(ServerRequestInterface $request, ?int $id = null): \Framework\Validator
    {
        return new $this->validator($request, $this->table, $this->categorieTable, $id);
    }

    /**
     * delete images of an article
     * @param Article $article
     *
     * @return mixed
     */
    private function deleteImages(Article $article)
    {
        /** @var string[]|null $images */
        $images = $article->showImages(false);
        if ($images) {
            foreach ($images as $image) {
                $this->userUpload->delete($image);
            }
        }
    }

    /**
     * upload images
     * @param \Psr\Http\Message\UploadedFileInterface[] $files
     * @param Article $article
     *
     * @return mixed
     */
    private function uploadImages(array $files, Article $article)
    {
        $images = [];
        foreach ($files as $image) {
            $images[] = $this->userUpload->upload($image, bin2hex(random_bytes(16)));
        }
        if (!in_array(null, $images)) {
            $this->deleteImages($article);
            return implode(',', $images);
        } else {
            foreach (array_filter($images) as $image) {
                $this->userUpload->delete($image);
            }
            return $article->__get('images');
        }
    }
}
