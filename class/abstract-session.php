<?php

abstract class AbstractSession {

    protected $Field;

    /**
     * @return self
     */
    public function __construct()
    {
        session_start();
    }


    /**
     * @param string $name
     * @return string|null
     */
    public function getProp($name)
    {
        return $_SESSION[$name] ?? null;
    }


    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function setProp($name, $value = null)
    {
        $_SESSION[$name] = $value;
    }


    /**
     * @return void
     */
    public function logout()
    {
        session_destroy();
    }

} // class AbstractSession
