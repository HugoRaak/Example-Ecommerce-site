<?php declare(strict_types=1);
namespace Framework\Database;

use Pagerfanta\Adapter\AdapterInterface;

/**
 * @template T
 * @implements \Pagerfanta\Adapter\AdapterInterface<T>
 */
final readonly class PaginatedArrayQuery implements AdapterInterface
{
    /**
     * @param mixed[] $items
     */
    public function __construct(private array $items)
    {
    }

    /**
     * get the number of items
     */
    public function getNbResults(): int
    {
        return count($this->items);
    }

    /**
     * Retrieve items corresponding to the number of items per page
     *
     *
     * @return iterable<array-key, T>
     */
    public function getSlice(int $offset, int $length): iterable
    {
        return array_slice($this->items, $offset, $length);
    }
}
