<?php

require_once ("tableRow.php");

class table extends uiGenerator
{
    private string $title;
    private bool $isCollapsable;
    private array $header;
    private array $tableRows;

    public function __construct(){
        $this->title = "";
        $this->isCollapsable = false;
        $this->header = [];
        $this->tableRows = [];

        $this->cssRules = [
            "h3" => "",
            "details" => "",
            "summary" => "",
            "table" => "",
            "th" => "",
            "tr" => "",
            "td" => "",
            "button" => ""
        ];
    }

    public function assignCss(array $rules)
    {
        parent::assignCss($rules);

        foreach ($this->tableRows as $t){
            $t->assignCss($rules);
        }
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setIsCollapsable(bool $isCollapsable): void
    {
        $this->isCollapsable = $isCollapsable;
    }

    public function addHeader(string ...$header): void
    {
        if (gettype($header) == "array")
            foreach ($header as $h)
                $this->header[] = $h;
        else
            $this->header[] = $header;
    }

    public function addTableRows(tableRow ...$row){
        if (gettype($row) == "array")
            foreach ($row as $r) {
                $r->assignCss($this->cssRules);
                $this->tableRows[] = $r;
            }
        else
            $this->tableRows[] = $row;
    }

    public function display(){
        echo $this->getHtmlElemStr("h3", $this->title);
        if ($this->isCollapsable){
            echo $this->getEmptyHtmlElemStr("details", "", 'open=""');
            echo $this->getHtmlElemStr("summary", "", "", 'data-open="Close" data-close="Expand"');
        }

        echo $this->getEmptyHtmlElemStr("table");
        echo $this->getEmptyHtmlElemStr("tr");

        foreach ($this->header as $h){
            echo $this->getHtmlElemStr("th", $h);
        }

        echo "</tr>";

        foreach ($this->tableRows as $t){
            $t->display();
        }

        echo "</table>";

        if ($this->isCollapsable)
            echo "</details>";
    }
}