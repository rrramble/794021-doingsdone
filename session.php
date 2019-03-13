<?php

class Session {

    public function __construct()
    {
        session_start();
    }

    public function getCustomProp($name)
    {
        if (!$name || !isset($_SESSION[$name])) {
            return null;
        };
        return $_SESSION[$name];
    }

    public function getUserData()
    {
        if (!$this->isAuthenticated()) {
            return null;
        };

        if (
            !isset($_SESSION["user"]) ||
            !isset($_SESSION["user"]["email"]) ||
            !isset($_SESSION["user"]["userName"]) ||
            !isset($_SESSION["user"]["id"])
        ) {
            return null;
        };

        $result["email"] = $_SESSION["user"]["email"];
        $result["userName"] = $_SESSION["user"]["userName"];
        $result["id"] = $_SESSION["user"]["id"];
        return $result;
    }

    public function getUserId()
    {
        return $result["id"] = (integer)($_SESSION["user"]["id"] ?? null);
    }

    private function isAuthenticated()
    {
        return isset($_SESSION["user"]);
    }

    public function setCustomProp($name, $value = null)
    {
        if (!$name) {
            return;
        };

        $_SESSION[(string)$name] = (string)$value;
    }

    public function setUserData($props)
    {
        if (
            !$props ||
            !isset($props["email"]) ||
            !isset($props["userName"]) ||
            !isset($props["id"])
        ) {
            return;
        };

        $_SESSION["user"]["email"] = $props["email"] ?? null;
        $_SESSION["user"]["userName"] = $props["userName"] ?? null;
        $_SESSION["user"]["id"] = $props["id"] ?? null;
    }

    public function logout()
    {
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
        };
    }

} // class Session
