<?php


class dbContains
{
    private string $val;
    public function __construct (string $contains){
        $this->val = $contains;
    }

    public function getContainsStr(){
        return $this->val;
    }
}