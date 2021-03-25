<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/sqlModel.php");

class shoppingcartDB extends sqlModel
{
    private int $id;
    private string $url;
    private DateTime $createDate;

    protected const sqlTableName = "shoppingcart";
    protected const sqlFields = ["id", "url", "createDate"];


    public function __construct()
    {
        $this->id = 0;
        $this->url = "";
        $this->createDate = new DateTime();
        return $this;
    }


    public function constructFull(int $id, string $url, $createDate)
    {
        $date = DateTime::createFromFormat('Y-m-d', $createDate);
        $this->id = $id;
        $this->url = $url;
        $this->createDate = $date;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "url" => $this->url,
            "createDate" => $this->createDate
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "url"],
            $sqlRes[self::sqlTableName . "createDate"]
        );
    }
}