<?php
namespace Framework\Session;

interface SessionInterface
{
    /**
     * retrieve an information in session
     * @param mixed $default=null
     *
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * add a key in session
     *
     */
    public function set(string $key, mixed $value): void;

    /**
     * delete a key in session
     *
     */
    public function delete(string $key): void;
}
