<?php


abstract class baseService
{
    protected dynamicQueryGen $db;

    public function __construct($model){
        $this->db = new dynamicQueryGen($model);
    }
}