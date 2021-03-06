<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/tableInterface.php");
require_once ("uiGenerator.php");

class tableGenerator extends uiGenerator
{
    private tableInterface $generator;

    public function __construct (tableInterface $generator){
        $this->generator = $generator;

        $this->cssRules = [
            "h3" => "",
            "table" => "",
            "tr" => "",
            "th" => "",
            "td" => "",
            "details" => "",
            "summary" => "",
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

    public function generate(){
        $table = $this->generator->getTableContent();

        // TODO: add strip tags to input

        foreach ($table["sections"] as $k => $v){
            echo $this->getHtmlElemStr("h3", $k);
            echo $this->getEmptyEtmlElemStr("details", "", 'open=""');
            echo $this->getHtmlElemStr("summary", "", "", 'data-open="Close" data-close="Expand"');
            echo $this->getEmptyEtmlElemStr("table", "", );

            echo $this->getEmptyEtmlElemStr("tr");

            foreach ($table["header"] as $h){
                echo $this->getHtmlElemStr("th", $h);
            }

            echo '</tr>';

            foreach ($v as $r){
                echo $this->getEmptyEtmlElemStr("tr");
                foreach ($r as $c){
                    echo $this->getHtmlElemStr("td", $c);
                }
                echo '</tr>';
            }

            echo '</table>';
            echo '</details>';
        }
    }
}