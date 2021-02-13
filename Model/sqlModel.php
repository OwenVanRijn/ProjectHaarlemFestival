<?php


abstract class sqlModel
{
    protected const sqlTableName = "";
    protected const sqlPrimaryKey = "id";
    protected const sqlFields = "";
    protected const sqlLinks = [];

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

    public abstract function sqlGetFields();

    public abstract static function sqlParse(array $sqlRes) : self;

    public static function sqlParseFunc() {
        return function (array $sqlRes) : sqlModel {
            return static::sqlParse($sqlRes);
        };
    }

    public abstract function getId();
}