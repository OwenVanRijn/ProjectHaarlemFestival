<?php

require_once ("sqlModel.php");

abstract class htmlModel extends sqlModel
{
    public abstract static function toHtmlHeader();
    public abstract function toHtmlValueArray();

    public function toHtmlArray(array $exclude=[]){
        $header = static::toHtmlHeader();
        foreach ($exclude as $f){
            if (isset($header[$f]))
                unset($header[$f]);
        }

        $value = $this->toHtmlValueArray();

        $res = [];

        foreach ($header as $k => $v){
            $res[$k] = ["type" => $v, "value" => $value[$k]];
        }

        return $res;
    }
}