<?php
require_once("sqlModel.php");

class jazzband extends sqlModel
{
    private int $id;
    private string $name;
    private string $description;


    protected const sqlTableName = "jazzband";
    protected const sqlFields = ["id", "name", "description"];


    public function constructFull(int $id, string $name, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        return $this;
    }


    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "name"],
            $sqlRes[self::sqlTableName . "description"]
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

 
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    public function getDescription()
    {
        return $this->description;
    }


    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
