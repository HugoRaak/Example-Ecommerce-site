<?php
namespace Framework\Auth;

use App\Auth\Database\Entity\User;

interface AuthInterface
{
    /**
     * @return User|null
     */
    public function getUser(): ?User;
}
