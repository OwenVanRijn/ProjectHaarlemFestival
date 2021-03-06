<?php


abstract class uiGenerator
{
    protected array $cssRules;

    protected function getHtmlElemStr(string $name, string $content, string $addCSS = "", string $extraTags = ""){
        return '<'. $name . ' ' . $extraTags .  ' class="' . $this->cssRules[$name] . " " . $addCSS .'">' . $content . "</$name>";
    }

    protected function getEmptyEtmlElemStr(string $name, string $addCSS = "", string $extraTags = ""){
        return '<'. $name . ' ' . $extraTags . ' class="' . $this->cssRules[$name] . " " . $addCSS .'">';
    }

    public function assignCss(array $rules){
        foreach ($rules as $k => $v){
            if (array_key_exists($k, $this->cssRules)){
                $this->cssRules[$k] = $v;
            }
        }
    }
}