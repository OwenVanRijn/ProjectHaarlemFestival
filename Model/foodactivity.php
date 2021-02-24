<?php

require_once ("sqlModel.php");
require_once ("restaurant.php");
require_once ("activity.php");
require_once ("restaurant.php");
require_once ("htmlTypeEnum.php");


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
            restaurant::sqlParse($sqlRes),
            activity::sqlParse($sqlRes)
        );
    }

    public function toJsonArray(){
        return [
            "activity" => [
                "id" => [
                    "type" => htmlTypeEnum::hidden,
                    "value" => $this->id
                ],

            ]
            // TODO: Fill in json array. also how are we gonna edit the subclasses? Maybe [[class] => [fields => val], [class]...]. Also port this to activity and restaurant classes
        ];
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