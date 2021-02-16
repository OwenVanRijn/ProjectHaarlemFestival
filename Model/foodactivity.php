<?php

require_once ("sqlModel.php");

class foodactivity extends sqlModel
{
    private int $id;
    private restaurant $restaurant;
    private int $activityId;

    protected const sqlTableName = "foodactivity";
    protected const sqlFields = ["id", "restaurantId", "activityId"];
    protected const sqlLinks = ["restaurantId" => restaurant::class];

    public function __construct()
    {
        $this->id = -1;
        $this->restaurant = null;
        $this->activityId = -1;
    }

    public function constructFull(int $id, restaurant $restaurant, int $activityId)
    {
        $this->id = $id;
        $this->restaurant = $restaurant;
        $this->activityId = $activityId;
        return $this;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "restaurantId" => $this->restaurant->getId(),
            "activityId" => $this->activity->getId()
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


    public function getRestaurant()
    {
        return $this->restaurant;
    }


    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;

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