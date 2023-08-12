<?php declare(strict_types=1);
namespace App\Auth\Database\Entity;

use Framework\Database\Entity\Entity;

final class Role extends Entity
{
    private ?int $id = null;

    private ?string $name = null;

    protected function getId() : ?int
    {
        return $this->id;
    }

    protected function getName(): ?string
    {
        return $this->name;
    }
}
