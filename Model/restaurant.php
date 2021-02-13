<?php
require_once ("sqlModel.php");

class restaurant extends sqlModel
{
    private int $id;
    private string $name;
    private string $description;
    private int $phoneNumber;

    protected const sqlTableName = "restaurant";
    protected const sqlFields = ["id", "name", "description", "phonenumber"];

    /**
     * restaurant constructor.
     * @param int $id
     * @param string $name
     * @param string $description
     * @param int $phoneNumber
     * @return restaurant
     */
    public function constructFull(int $id, string $name, string $description, int $phoneNumber)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->phoneNumber = $phoneNumber;
        return $this;
    }


    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "phonenumber" => $this->phoneNumber
        ];
    }

    public static function sqlParse(array $sqlRes): restaurant
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "name"],
            $sqlRes[self::sqlTableName . "description"],
            $sqlRes[self::sqlTableName . "phonenumber"]);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}