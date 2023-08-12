<?php declare(strict_types=1);
namespace Framework\Router;

/**
 * Class Route
 * represent a matched route
 */
class Route
{
    /**
     * @param string|callable $callback
     * @param mixed[] $params
     */
    public function __construct(
        readonly private ?string $name,
        readonly private mixed $callback,
        readonly private array $params
    ) {
    }

    /**
     * get the name of the route
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * get the callback associate to the route
     * @return string|callable
     */
    public function getCallback(): string|callable
    {
        return $this->callback;
    }

    /**
     * retrieve the url parameters
     * @return string[]
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
