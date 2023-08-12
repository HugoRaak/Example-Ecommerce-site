<?php
namespace Framework\Session;

interface SessionInterface
{
    /**
     * retrieve an information in session
     * @param string $key
     * @param mixed $default=null
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * add a key in session
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $key, mixed $value): void;

    /**
     * delete a key in session
     * @param string $key
     *
     * @return void
     */
    public function delete(string $key): void;
}
