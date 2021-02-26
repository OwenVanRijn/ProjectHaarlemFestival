<?php


class appException extends Exception
{
    public function getError(){
        return $this->getMessage();
    }
}