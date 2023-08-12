<?php

declare(strict_types=1);

namespace Framework\Database\Entity;

final class Article extends Entity
{
    private ?int $id = null;

    private ?string $name = null;

    private ?string $slug = null;

    private ?string $images = null;

    private ?string $description = null;

    private ?float $price = null;

    /**
     * @var \DateTime|string|null
     */
    private mixed $created_at = null;

    /**
     * @var \DateTime|string|null
     */
    private mixed $updated_at = null;

    private ?int $user_id = null;

    private ?int $categorie_id = null;

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

    protected function getImages(): ?string
    {
        return $this->images;
    }

    protected function getPrice(): ?float
    {
        return $this->price;
    }
    protected function setPrice(?float $price): self
    {
        $this->price = $price;
        return $this;
    }

    protected function getDescription(): ?string
    {
        return $this->description;
    }
    protected function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    protected function getCreatedAt(): \DateTime|string|null
    {
        if ($this->created_at && is_string($this->created_at)) {
            $this->created_at = new \DateTime($this->created_at);
        }
        return $this->created_at;
    }

    protected function getUpdatedAt(): \DateTime|string|null
    {
        if ($this->updated_at && is_string($this->updated_at)) {
            $this->updated_at = new \DateTime($this->updated_at);
        }
        return $this->updated_at;
    }

    protected function getCategorieId(): ?int
    {
        return $this->categorie_id;
    }
    protected function setCategorieId(?int $categorie_id): self
    {
        $this->categorie_id = $categorie_id;
        return $this;
    }

    protected function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * get first image in string or all images in array
     *
     * @return string[]|string|null
     */
    public function showImages(bool $one = true): array|string|null
    {
        if ($this->images) {
            if ($one) {
                return explode(',', $this->images)[0];
            }
            return explode(',', $this->images);
        }
        return null;
    }

    /**
     * Retrieve a image in the right format
     *
     */
    public function getImageFormat(string $image, string $format): string
    {
        $pathInfo = pathinfo($image);
        $extension = $pathInfo['extension'] ?? null;
        if ($extension) {
            $filename = $pathInfo['filename'];
            return '/uploads/article/' . $filename . '_' . $format . '.' . $extension;
        }
        return '';
    }
}
