<?php

require_once ("sqlModel.php");

class restaurantType extends sqlModel
{
    private int $id;
    private string $name;

    protected const sqlTableName = "restauranttypes";
    protected const sqlFields = ["id", "name"];

    /**
     * restaurantType constructor.
     * @param int $id
     * @param string $name
     * @return restaurantType
     */
    public function constructFull(int $id, string $name)
    {
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

    public static function sqlParse(array $sqlRes): restaurantType
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "name"]);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}