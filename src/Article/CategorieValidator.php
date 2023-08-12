<?php declare(strict_types=1);
namespace App\Article;

use Framework\Database\Table\CategorieTable;
use Framework\Validator;
use Psr\Http\Message\ServerRequestInterface;

final class CategorieValidator extends Validator
{
    /**
     * add rules about categorie to the validator
     *
     * @param ServerRequestInterface $request
     * @param CategorieTable $table
     * @param int|null $id=null
     */
    public function __construct(
        readonly ServerRequestInterface $request,
        readonly CategorieTable $table,
        readonly ?int $id = null
    ) {
        $data = $request->getParsedBody();
        is_array($data) ? parent::__construct($data) : parent::__construct([]);
        $this
            ->rule('required', ['name', 'slug'])
            ->rule('lengthBetween', ['name', 'slug'], 2, 40)
            ->rule('regex', 'slug', '/^[a-z0-9\-]+$/')
            ->rule(function ($field, $value) use ($table, $id) {
                return !$table->exists($field, $value, $id);
            }, ['name', 'slug'], 'Cette valeur est déjà utilisé')
            ->setPrependLabels(false);
    }
}
