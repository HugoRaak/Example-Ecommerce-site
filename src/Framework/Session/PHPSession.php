<?php declare(strict_types=1);
namespace Framework\Session;

final class PHPSession implements SessionInterface
{
    /**
     * ensure that the session is started
     *
     */
    private function ensureStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * retrieve an information in session
     * @param mixed $default=null
     *
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $this->ensureStarted();
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     * add a key in session
     *
     */
    public function set(string $key, mixed $value): void
    {
        $this->ensureStarted();
        $_SESSION[$key] = $value;
    }

    /**
     * delete a key in session
     *
     */
    public function delete(string $key): void
    {
        $this->ensureStarted();
        if (array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }
}
