<?php declare(strict_types=1);
namespace Framework\Session;

final class PHPSession implements SessionInterface
{
    /**
     * ensure that the session is started
     *
     * @return void
     */
    private function ensureStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * retrieve an information in session
     * @param string $key
     * @param mixed $default=null
     *
     * @return mixed
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
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->ensureStarted();
        $_SESSION[$key] = $value;
    }

    /**
     * delete a key in session
     * @param string $key
     *
     * @return void
     */
    public function delete(string $key): void
    {
        $this->ensureStarted();
        if (array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }
}
