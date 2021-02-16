<?php

require_once ("sqlModel.php");

class foodactivity extends sqlModel
{
    private int $id;
    private int $restaurantId;
    private int $activityId;

    protected const sqlTableName = "foodactivity";
    protected const sqlFields = ["id", "restaurantId", "activityId"];

    public function __construct()
    {
        $this->id = -1;
        $this->restaurantId = -1;
        $this->activityId = -1;
    }

    public function constructFull(int $id, int $restaurantId, int $activityId)
    {
        $this->id = $id;
        $this->restaurantId = $restaurantId;
        $this->activityId = $activityId;
        return $this;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "restaurantId" => $this->restaurantId,
            "activityId" => $this->activityId
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "restaurantId"],
            $sqlRes[self::sqlTableName . "activityId"]
        );
    }


    public function getId()
    {
        return $this->id;
    }


    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }


    public function getRestaurantId()
    {
        return $this->restaurantId;
    }


    public function setRestaurantId($restaurantId)
    {
        $this->restaurantId = $restaurantId;

        return $this;
    }

    public function getActivityId()
    {
        return $this->activityId;
    }


    public function setActivityId($activityId)
    {
        $this->activityId = $activityId;

        return $this;
    }
}