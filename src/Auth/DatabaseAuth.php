<?php declare(strict_types=1);
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
     * @param string $username
     * @param string $password
     *
     * @return User|null
     */
    public function login(string $username, string $password): ?User
    {
        if (empty($username) || empty($password)) {
            return null;
        }
        try {
            /** @var User $user */
            $user = $this->userTable->findBy('username', $username);
        } catch (NoRecordException $e) {
            return null;
        }
        if (password_verify($password, $user->__get('password'))) {
            $this->session->set('auth.user', $user->__get('id'));
            return $user;
        }
        return null;
    }

    /**
     * logout the user
     * @return void
     */
    public function logout(): void
    {
        $this->session->delete('auth.user');
    }

    /**
     * return the current user
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->user) {
            return $this->user;
        }
        $userId = $this->session->get('auth.user');
        if ($userId) {
            try {
                /** @var User $user */
                $user = $this->userTable->find($userId);
                $this->user = $user;
                return $this->user;
            } catch (NoRecordException $e) {
                $this->session->delete('auth.user');
                return null;
            }
        }
        return null;
    }

    /**
     * check if a user is an admin
     * @return bool
     */
    public function isAdmin(): bool
    {
        $user = $this->getUser();
        if ($user) {
            if ($this->roleTable->findFromTable('user', $user->__get('id'))->__get('name') === 'admin') {
                return true;
            }
        }
        return false;
    }
}
