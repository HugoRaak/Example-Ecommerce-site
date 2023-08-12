<?php declare(strict_types=1);
namespace Framework\Database\Table;

use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use Framework\Database\PaginatedArrayQuery;
use Framework\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;

/**
 * @template T of object
 * @template E of \Framework\Database\Entity\Entity
 */
class Table
{
    protected ?string $table = null;

    /** @var class-string<E>|null */
    protected ?string $entity = null;

    public function __construct(readonly private Query $fpdo)
    {
    }

    /**
     * get actual Table
     * @return string|null
     */
    public function getTable(): ?string
    {
        return get_called_class();
    }

    /**
     * get actual Entity
     * @return string|null
     */
    public function getEntity(): ?string
    {
        return $this->entity;
    }

    /**
     * get the fluentPDO instance
     * @return Query
     */
    public function getFpdo(): Query
    {
        return $this->fpdo;
    }

    /**
     * get the last insert id
     * @return string
     */
    public function lastInsertId(): string|false
    {
        return $this->fpdo->getPdo()->lastInsertId();
    }

    /**
     * find an element by ID
     * @param int $id
     *
     * @return (E is null ? object : E)
     */
    public function find(int $id): object
    {
        $query = $this->makeQuery()
            ->where('id', $id);
        return $this->fetchOrFail($query);
    }

    /**
     * find a element by a field
     * @param string $field
     * @param mixed $value
     *
     * @return (E is null ? object : E)
     */
    public function findBy(string $field, mixed $value): object
    {
        $query = $this->makeQuery()
            ->where($field, $value);
        return $this->fetchOrFail($query);
    }

    /**
     * get list associative of id and name
     * @return mixed[]
     */
    public function findList(): array
    {
        $results = $this->makeQuery()
            ->select(['id', 'name'])
            ->fetchAll();
        $list = [];
        foreach (is_array($results) ? $results : [] as $result) {
            $list[$result->id] = $result->name;
        }
        return $list;
    }

    /**
     * find all row from a table, can has settings and limit
     * @param string|null $orderBy
     * @param int|null $limit
     *
     * @return ((E is null ? object : E))[]
     */
    public function findAll(?string $orderBy = null, ?int $limit = null): array
    {
        $query = $this->makeQuery();
        if ($orderBy) {
            $query->orderBy($orderBy);
        }
        if ($limit) {
            $query->limit($limit);
        }
        $statement = $query->execute();
        if ($this->entity) {
            $statement->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }
        return $statement->fetchAll();
    }

    /**
     * find a row from a join element id
     * @param string $fromTable
     * @param int $id
     *
     * @return (E is null ? object : E)
     */
    public function findFromTable(string $fromTable, int $id): object
    {
        $statement = $this->fpdo
            ->from($fromTable)
            ->select($this->table . '.*')
            ->where($fromTable . '.id', $id)
            ->execute();
        if ($this->entity) {
            $statement->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }
        return $statement->fetch();
    }

    /**
     * count the number of row
     * @return int
     */
    public function count(): int
    {
        return (int)$this->countQuery()->fetchColumn();
    }

    /**
     * Get pagination of items
     *
     * @param int $perPage
     * @param int $currentPage
     * @param Select|null $query = null
     * @param Select|null $countQuery = null
     *
     * @return Pagerfanta<\Pagerfanta\PagerfantaInterface> items paginate
     */
    public function findPaginated(
        int $perPage,
        int $currentPage,
        ?Select $query = null,
        ?Select $countQuery = null
    ): Pagerfanta {
        if ($query === null) {
            $query = $this->paginationQuery();
        }
        if ($countQuery === null) {
            $countQuery = $this->countQuery();
        }
        $query = new PaginatedQuery($query, $countQuery, $this->entity);
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    /**
     * return query for PaginatedQuery
     * @return Select
     */
    protected function paginationQuery(): Select
    {
        return $this->makeQuery();
    }

    /**
     * get paginated articles from an array
     *
     * @param int $perPage
     * @param int $currentPage
     * @param object[] $items
     *
     * @return Pagerfanta<\Pagerfanta\PagerfantaInterface>
     */
    public function findPaginatedArray(int $perPage, int $currentPage, array $items): Pagerfanta
    {
        $query = (new PaginatedArrayQuery($items));
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    /**
     * verifiy if a value of a field already exist in the table
     * @param string $field
     * @param mixed $value
     * @param int|null $except
     *
     * @return bool
     */
    public function exists(string $field, mixed $value, ?int $except = null): bool
    {
        $query = $this->countQuery()->where($field, $value);
        if ($except !== null) {
            $query->where('NOT id', $except);
        }
        return $query->fetchColumn() > 0;
    }

    /**
     * update a row
     *
     * @param mixed[] $params
     * @param int $id
     *
     * @return void
     */
    public function update(array $params, int $id): void
    {
        $this->fpdo->update($this->table, $params, $id)->execute();
    }

    /**
     * insert a row
     *
     * @param mixed[] $values
     *
     * @return void
     */
    public function insert(array $values) : void
    {
        $this->fpdo->insertInto($this->table, $values)->execute();
    }

    /**
     * delete a row
     *
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->fpdo->deleteFrom($this->table, $id)->execute();
    }

    /**
     * test if a record was found and return it if yes
     * @param Select $query
     *
     * @return (E is null ? object : E)
     * 
     * @throws NoRecordException if there is no record found
     */
    protected function fetchOrFail(Select $query): object
    {
        $statement = $query->execute();
        if ($this->entity) {
            $statement->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }
        $result = $statement->fetch();
        if ($result === false) {
            throw new NoRecordException();
        }
        return $result;
    }

    /**
     * prepare a select query
     * @return Select
     */
    protected function makeQuery(): Select
    {
        return $this->fpdo->from($this->table);
    }

    /**
     * make a count query
     * @return Select
     */
    protected function countQuery(): Select
    {
        return $this->makeQuery()
            ->select(null)
            ->select('COUNT(id)');
    }
}
