<?php

require_once ("sqlModel.php");
require_once ("restaurant.php");
require_once ("activity.php");require_once ("restaurant.php");


class foodactivity extends sqlModel
{
    private int $id;
    private restaurant $restaurant;
    private activity $activity;

    protected const sqlTableName = "foodactivity";
    protected const sqlFields = ["id", "restaurantId", "activityId"];
    protected const sqlLinks = ["restaurantId" => restaurant::class, "activityId" => activity::class];

    public function __construct()
    {
        $this->id = -1;
        $this->restaurant = null;
        $this->activity = null;
    }

    public function constructFull(int $id, restaurant $restaurant, activity $activity)
    {
        $this->id = $id;
        $this->restaurant = $restaurant;
        $this->activity = $activity;
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

    public function getActivity()
    {
        return $this->activity;
    }


    public function setActivityId($activity)
    {
        $this->activityId = $activity;

        return $this;
    }
}