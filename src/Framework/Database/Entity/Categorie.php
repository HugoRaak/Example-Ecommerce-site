<?php declare(strict_types=1);
namespace Framework\Database\Entity;

final class Categorie extends Entity
{
    private ?int $id = null;

    private ?string $name = null;

    private ?string $slug = null;

    protected function getId(): ?int
    {
        return $this->id;
    }

    protected function getName(): ?string
    {
        return $this->name;
    }
    protected function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    protected function getSlug(): ?string
    {
        return $this->slug;
    }
    protected function setSlug(?string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }
}
