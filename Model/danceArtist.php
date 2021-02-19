<?php

require_once ("sqlModel.php");

class danceArtist extends sqlModel
{
    private int $id;
    private string $name;

    protected const sqlTableName = "danceartist";
    protected const sqlFields = ["id", "name"];

    public function constructFull(int $id, string $name){
        $this->id = $id;
        $this->name = $name;
        return $this;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "name" => $this->name
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "name"]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}