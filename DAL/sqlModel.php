<?php


abstract class sqlModel
{
    // The variables tableName, primaryKey, fields need to exist

    protected const sqlTableName = "";
    protected const sqlPrimaryKey = "";
    protected const sqlFields = "";
    protected const sqlLinks = "";

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

    public abstract static function sqlParse(array $sqlRes) : sqlModel;

    public static function sqlParseFunc() {
        return function (array $sqlRes) : sqlModel {
            return static::sqlParse($sqlRes);
        };
    }
}