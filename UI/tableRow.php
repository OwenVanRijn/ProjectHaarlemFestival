<?php

require_once ("uiGenerator.php");

class tableRow extends uiGenerator
{
    private array $contents;

    public function __construct(){
        $this->cssRules = [
            "tr" => "",
            "td" => "",
            "button" => ""
        ];

        $this->contents = [];
    }

    public function addString(string ...$str){
        if (gettype($str) == "array")
            foreach ($str as $r)
                $this->contents[] = $r;
        else
            $this->contents[] = $str;
    }

    public function addButton(string $onclick, string $inner){
        $this->contents[] = $this->getHtmlElemStr("button", $inner, "", "onclick=\"$onclick\"");
    }

    public function display(){
        echo $this->getEmptyHtmlElemStr("tr");

        foreach ($this->contents as $c){
            echo $this->getHtmlElemStr("td", $c);
        }

        echo "</tr>";
    }
}