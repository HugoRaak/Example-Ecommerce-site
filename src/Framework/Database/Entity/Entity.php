<?php declare(strict_types=1);
namespace Framework\Database\Entity;

class Entity
{
    /**
     * get an entity property
     *
     */
    public function __get(string $key): mixed
    {
        $method = 'get' . $this->getKey($key);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        return false;
    }

    /**
     * set a value to an entity property
     *
     */
    public function __set(string $key, mixed $value): void
    {
        $method = 'set' . $this->getKey($key);
        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }

    /**
     * transform a key in CamelCase
     *
     */
    private function getKey(string $key): string
    {
        return implode('', array_map('ucfirst', explode('_', $key)));
    }

    /**
     * hydrate an entity with params
     *
     * @template T of object
     * @param T $entity
     * @param mixed[] $params
     *
     * @return T
     */
    public static function hydrate(object $entity, array $params): object
    {
        foreach ($params as $key => $value) {
            if (strpos($key, '_at') === false && property_exists($entity, $key)) {
                $value = $value === 'null' ? ($value = null) : self::typeCast($key, $value, $entity);
                $entity->__set($key, $value);
            }
        }
        return $entity;
    }

    /**
     * type casting the value in the right type for the entity
     *
     */
    private static function typeCast(string $key, string $value, object $entity): mixed
    {
        /** @var \ReflectionNamedType $propertyType */
        $propertyType = (new \ReflectionProperty($entity, $key))->getType();
        if ($propertyType instanceof \ReflectionNamedType) {
            $typeName = $propertyType->getName();
            if ($typeName === 'int') {
                $value = (int)$value;
            } elseif ($typeName === 'float') {
                $value = (float)$value;
            }
        }
        return $value;
    }
}
