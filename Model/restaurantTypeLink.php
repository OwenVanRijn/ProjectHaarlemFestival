<?php

require_once ("sqlModel.php");
require_once ("restaurant.php");
require_once ("restaurantType.php");

class restaurantTypeLink extends sqlModel
{
    private int $id;
    private restaurant $restaurant;
    private restaurantType $type;

    protected const sqlTableName = "restauranttypelink";
    protected const sqlFields = ["id", "restauranttypesid", "restaurantid"];
    protected const sqlLinks = ["restaurantid" => restaurant::class, "restauranttypesid" => restaurantType::class];

    /**
     * restaurantTypeLink constructor.
     * @param int $id
     * @param restaurant $restaurant
     * @param restaurantType $type
     * @return restaurantTypeLink
     */
    public function constructFull(int $id, restaurant $restaurant, restaurantType $type)
    {
        $this->id = $id;
        $this->restaurant = $restaurant;
        $this->type = $type;
        return $this;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "restaurantid" => $this->restaurant->getId(),
            "restauranttypesid" => $this->type->getId()
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            restaurant::sqlParse($sqlRes),
            restaurantType::sqlParse($sqlRes));
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return restaurant
     */
    public function getRestaurant(): restaurant
    {
        return $this->restaurant;
    }

    /**
     * @return restaurantType
     */
    public function getType(): restaurantType
    {
        return $this->type;
    }
}