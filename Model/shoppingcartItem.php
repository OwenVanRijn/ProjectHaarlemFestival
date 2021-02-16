<?php

require_once("sqlModel.php");
require_once("shoppingcart.php");
require_once("activity.php");

class shoppingcartItem extends sqlModel
{
    private int $id;
    private shoppingcart $shoppingcart;
    private activity $activity;

    protected const sqlTableName = "shoppingcartItem";
    protected const sqlFields = ["id", "shoppingcart", "activity"];

    public function constructFull(int $id, shoppingcart $shoppingcart, activity $activity)
    {
        $this->id = $id;
        $this->shoppingcart = $shoppingcart;
        $this->activity = $activity;
        return $this;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "shoppingcartId" => $this->shoppingcart->getId(),
            "activityId" => $this->activity->getId()
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "shoppingcartId"],
            $sqlRes[self::sqlTableName . "activityId"]
        );
    }

    public function getId(): int
    {
        return $this->id;
    }
}