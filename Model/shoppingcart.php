<?php

require_once("sqlModel.php");

class shoppingcart extends sqlModel
{
    private int $id;
    private string $url;
    private DateTime $createDate;

    protected const sqlTableName = "shoppingcart";
    protected const sqlFields = ["id", "url", "createDate"];

    public function constructFull(int $id, string $url, DateTime $createDate)
    {
        $this->id = $id;
        $this->url = $url;
        $this->createDate = $createDate;
        return $this;
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

    public function getId(): int
    {
        return $this->id;
    }
}
