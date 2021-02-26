<?php

// This code should not exist. Fuck php

abstract class singleton
{
    private static self $single;

    protected abstract function construct();

    public static function getInstance(){
        if (!isset(self::$single)){
            self::$single = new static();
            self::$single->construct();
        }

        return self::$single;
    }
}