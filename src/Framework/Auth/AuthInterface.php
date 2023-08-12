<?php

namespace Framework\Auth;

use App\Auth\Database\Entity\User;

interface AuthInterface
{
    public function getUser(): ?User;
}
