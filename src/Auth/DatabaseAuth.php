<?php

declare(strict_types=1);

namespace App\Auth;

use App\Auth\Database\Entity\User;
use App\Auth\Database\Table\RoleTable;
use App\Auth\Database\Table\UserTable;
use Framework\Auth\AuthInterface;
use Framework\Database\Table\NoRecordException;
use Framework\Session\SessionInterface;

final class DatabaseAuth implements AuthInterface
{
    private ?User $user = null;

    public function __construct(
        readonly private UserTable $userTable,
        readonly private SessionInterface $session,
        readonly private RoleTable $roleTable
    ) {
    }

    /**
     * login the user if it possible
     *
     */
    public function login(string $username, string $password): ?User
    {
        if ($username === '' || $password === '') {
            return null;
        }
        try {
            /** @var User $user */
            $user = $this->userTable->findBy('username', $username);
        } catch (NoRecordException) {
            return null;
        }
        if (password_verify($password, (string) $user->__get('password'))) {
            $this->session->set('auth.user', $user->__get('id'));
            return $user;
        }
        return null;
    }

    /**
     * logout the user
     */
    public function logout(): void
    {
        $this->session->delete('auth.user');
    }

    /**
     * return the current user
     */
    public function getUser(): ?User
    {
        if ($this->user instanceof \App\Auth\Database\Entity\User) {
            return $this->user;
        }
        $userId = $this->session->get('auth.user');
        if ($userId) {
            try {
                /** @var User $user */
                $user = $this->userTable->find($userId);
                $this->user = $user;
                return $this->user;
            } catch (NoRecordException) {
                $this->session->delete('auth.user');
                return null;
            }
        }
        return null;
    }

    /**
     * check if a user is an admin
     */
    public function isAdmin(): bool
    {
        $user = $this->getUser();
        return $user instanceof \App\Auth\Database\Entity\User &&
               $this->roleTable->findFromTable('user', $user->__get('id'))->__get('name') === 'admin';
    }
}
