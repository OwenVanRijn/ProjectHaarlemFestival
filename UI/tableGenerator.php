<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/tableInterface.php");

class tableGenerator
{
    private tableInterface $generator;

    private array $cssRules;

    public function __construct (tableInterface $generator){
        $this->generator = $generator;

        $this->cssRules = [
            "h3" => "",
            "table" => "",
            "tr" => "",
            "th" => "",
            "td" => ""
        ];
    }

    /* Generated html format:

    <h3>section</h3>
    <table>
        <tr>
            <th>header</th>
        </tr>
        <tr>
            <td>content</td>
        </tr>
    </table>

    */

    public function assignCss(array $rules){
        foreach ($rules as $k => $v){
            if (array_key_exists($k, $this->cssRules)){
                $this->cssRules[$k] = $v;
            }
        }
    }

    private function getHtmlElemStr(string $name, string $content){
        return '<'. $name . ' class="' . $this->cssRules[$name] .'">' . $content . "</$name>";
    }

    public function generate(){
        $table = $this->generator->getContent();

        // TODO: add strip tags to input

        foreach ($table["sections"] as $k => $v){
            echo $this->getHtmlElemStr("h3", $k);
            echo '<table class="' . $this->cssRules["table"] . '">';

            echo '<tr class="'. $this->cssRules["tr"] .'">';

            foreach ($table["header"] as $h){
                echo $this->getHtmlElemStr("th", $h);
            }

            echo '</tr>';

            foreach ($v as $r){
                echo '<tr class="'. $this->cssRules["tr"] .'">';
                foreach ($r as $c){
                    echo $this->getHtmlElemStr("td", $c);
                }
                echo '</tr>';
            }

            echo '</table>';
        }
    }
}