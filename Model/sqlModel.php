<?php


abstract class sqlModel
{
    protected const sqlTableName = "";
    protected const sqlPrimaryKey = "id";
    protected const sqlFields = "";
    protected const sqlLinks = [];
    protected const sqlPrimaryIncrement = true;

    public static function sqlTableName() {
        return static::sqlTableName;
    }

    public static function sqlPrimaryKey(){
        return static::sqlPrimaryKey;
    }

    public static function sqlFields(){
        return static::sqlFields;
    }

    public static function sqlLinks(){
        return static::sqlLinks;
    }

    public static function sqlPrimaryIncrement(){
        return static::sqlPrimaryIncrement;
    }

    public abstract function sqlGetFields();

    public abstract static function sqlParse(array $sqlRes) : self;

    public static function sqlParseFunc() {
        return function (array $sqlRes) : sqlModel {
            return static::sqlParse($sqlRes);
        };
    }

    public abstract function getId();

    private function splitSQLMultiConcat(array $fields, string $sep, array $sqlRes){
        $fieldCount = count($fields);
        $result = [];

        $splitSqlRes = [];
        foreach ($fields as $field){
            $splitSqlRes[] = explode($sep, $sqlRes[$field]);
        }
        for ($i = 0; $i < $fieldCount; $i++){
            $result[$i] = [];
            for ($j = 0; $j < count($splitSqlRes); $j++){
                $result[$i][$fields[$j]] = $splitSqlRes[$j];
            }
        }

        return $result;
    }
}