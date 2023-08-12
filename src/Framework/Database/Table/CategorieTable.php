<?php declare(strict_types=1);
namespace Framework\Database\Table;

use Envms\FluentPDO\Queries\Select;
use Framework\Database\Entity\Categorie;

/**
 * @extends Table<static, Categorie>
 */
final class CategorieTable extends Table
{
    protected ?string $table = 'categorie';

    protected ?string $entity = Categorie::class;

    /**
     * return query for PaginatedQuery
     */
    protected function paginationQuery(): Select
    {
        return parent::paginationQuery()->orderBy('name ASC');
    }
}
