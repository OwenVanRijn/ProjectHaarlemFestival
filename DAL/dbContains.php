<?php


class dbContains
{
    private $val;

    /**
     * dbContains constructor.
     * @param string|string[] $contains
     */
    public function __construct ($contains){
        if (gettype($contains) == "string")
            $this->val = [$contains];
        else
            $this->val = $contains;
    }

    public function getContainsArray(){
        return $this->val;
    }

    public function genSql(string $tableVar){
        $query = "(";
        foreach ($this->val as $s){
            $query .= "position(? in " . $tableVar . ") > 0 OR ";
        }
        $query = substr($query, 0, -4);
        $query .= ")";

        return $query;
    }
}