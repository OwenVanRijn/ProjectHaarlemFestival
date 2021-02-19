<?php


abstract class uiGenerator
{
    protected array $cssRules;

    protected function getHtmlElemStr(string $name, string $content, string $addCSS = ""){
        return '<'. $name . ' class="' . $this->cssRules[$name] . " " . $addCSS .'">' . $content . "</$name>";
    }

    public function assignCss(array $rules){
        foreach ($rules as $k => $v){
            if (array_key_exists($k, $this->cssRules)){
                $this->cssRules[$k] = $v;
            }
        }
    }
}