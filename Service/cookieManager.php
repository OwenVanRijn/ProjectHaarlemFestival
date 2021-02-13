<?php


class cookieManager
{
    private string $target;

    /**
     * cookieManager constructor.
     * @param string $target
     */
    public function __construct(string $target)
    {
        $this->target = $target;
    }

    public function get(){
        if (!isset($_SESSION))
            session_start();

        if (isset($_SESSION[$this->target]))
            return $_SESSION[$this->target];

        if (isset($_COOKIE[$this->target])){
            $_SESSION[$this->target] = $_COOKIE[$this->target];
            return $_COOKIE[$this->target];
        }

        return null;
    }

    // 0 days does not store in a cookie
    public function set($val, int $days){
        if (!isset($_SESSION))
            session_start();

        $_SESSION[$this->target] = $val;
        if ($days > 0){
            setcookie($this->target, $val, time() + (86400 * $days), '/');
        }
    }

    public function del(){
        if (!isset($_SESSION))
            session_start();

        if (isset($_SESSION[$this->target]))
            unset($_SESSION[$this->target]);

        if (isset($_COOKIE[$this->target])){
            unset($_COOKIE[$this->target]);
            setcookie($this->target, null, -1, '/');
        }
    }
}