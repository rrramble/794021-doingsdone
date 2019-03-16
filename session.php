<?php

include_once("class/abstract-session.php");

class Session extends AbstractSession{

    /**
     * @return integer|null
     */
    public function getUserId()
    {
        return $this->getProp("userId");
    }


    /**
     * @return string|null
     */
    public function getUserName()
    {
        $this->getProp("userId");
    }


    /**
     * @return void
     */
    public function setUserId($value)
    {
        $this->setProp("userId", $value);
    }


    /**
     * @return void
     */
    public function setUserName($value)
    {
        $this->setProp("userName", $value);
    }

} // class Session
