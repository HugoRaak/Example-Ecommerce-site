<?php declare(strict_types=1);
namespace App\Auth\Database\Entity;

use Framework\Database\Entity\Entity;

final class User extends Entity
{
    private ?int $id = null;

    private ?string $username = null;

    private ?string $password = null;

    private ?string $email = null;

    private ?int $role_id = null;

    protected function getId(): ?int
    {
        return $this->id;
    }

    protected function getUsername(): ?string
    {
        return $this->username;
    }
    protected function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    protected function getEmail(): ?string
    {
        return $this->email;
    }
    protected function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    protected function getPassword(): ?string
    {
        return $this->password;
    }
    protected function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    protected function getRoleId(): ?int
    {
        return $this->role_id;
    }
}
