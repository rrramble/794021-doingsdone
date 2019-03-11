<?php

class Session {

    public function __construct()
    {
        session_start();
    }

    public function getCustomProp($name)
    {
        if (!isset($_SESSION[$name])) {
            return null;
        };
        return $_SESSION[$name];
    }

    public function getUserData()
    {
        if (!$this->isAuthenticated()) {
            return null;
        };

        $result["email"] = $_SESSION["user"]["email"];
        $result["userName"] = $_SESSION["user"]["userName"];
        $result["id"] = $_SESSION["user"]["id"];
        return $result;
    }

    private function isAuthenticated()
    {
        return isset($_SESSION["user"]);
    }

    public function setCustomProp($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function setUserData($props)
    {
        $_SESSION["user"]["email"] = $props["email"];
        $_SESSION["user"]["userName"] = $props["userName"];
        $_SESSION["user"]["id"] = $props["id"];
    }

    public function logout()
    {
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
        };
    }

} // class Session
