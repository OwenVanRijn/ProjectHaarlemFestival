<?php

require_once ("sqlModel.php");

class danceArtist extends sqlModel
{
    private int $id;
    private string $name;
    private string $description;

    protected const sqlTableName = "danceartist";
    protected const sqlFields = ["id", "name", "description"];

    public function __construct()
    {
        $this->id = -1;
    }

    public function constructFull(int $id, string $name, string $description){
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        return $this;
    }

    public function sqlGetFields()
    {
        $array = [
            "id" => $this->id,
        ];

        if (isset($this->name))
            $array["name"] = $this->name;

        if (isset($this->description))
            $array["description"] = $this->description;

        return $array;
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "name"],
            $sqlRes[self::sqlTableName . "description"]);
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

    public function getDescription(): string{
        return $this->description;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}