<?php

class Session {

    public function __construct()
    {
        session_start();
    }


    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getCustomProp($name)
    {
        if (!$name || !isset($_SESSION[$name])) {
            return null;
        };
        return $_SESSION[$name];
    }


    /**
     * @return array|null
     */
    public function getUserData()
    {
        if (!$this->isAuthenticated()) {
            return null;
        };

        if (
            !isset($_SESSION["user"]) ||
            !isset($_SESSION["user"]["userName"]) ||
            !isset($_SESSION["user"]["id"])
        ) {
            return null;
        };

        $result["userName"] = $_SESSION["user"]["userName"];
        $result["id"] = $_SESSION["user"]["id"];
        return $result;
    }


    /**
     * @return integer|null
     */
    public function getUserId()
    {
        if (!isset($_SESSION["user"]) || !isset($_SESSION["user"]["id"])) {
            return null;
        };
        return (integer)$_SESSION["user"]["id"];
    }


    /**
     * @return boolean
     */
    private function isAuthenticated()
    {
        return isset($_SESSION["user"]);
    }


    /**
     * @return void
     */
    public function setCustomProp($name, $value = null)
    {
        if (!$name) {
            return;
        };

        $_SESSION[(string)$name] = (string)$value;
    }


    /**
     * @param array $props
     *
     * @return void
     */
    public function setUserData($props)
    {
        if (
            !$props ||
            !isset($props["userName"]) ||
            !isset($props["id"])
        ) {
            return;
        };

        $_SESSION["user"]["userName"] = $props["userName"] ?? null;
        $_SESSION["user"]["id"] = $props["id"] ?? null;
    }


    /**
     * @return void
     */
    public function logout()
    {
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
        };
    }

} // class Session
