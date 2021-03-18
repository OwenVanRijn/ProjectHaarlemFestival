<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Model/sqlModel.php");
require_once($root . "/Model/shoppingcart.php");
require_once($root . "/Model/activity.php");

class shoppingcartItem extends sqlModel
{
    private shoppingcart $shoppingcart;
    private activity $activity; //same as activityId
    private int $amount; //hoeveel items

    protected const sqlTableName = "shoppingcartItem";
    protected const sqlFields = ["amount"];
    protected const sqlLinks = ["shoppingcartId" => shoppingcart::class, "activityId" => activity::class];

    public function constructFull(shoppingcart $shoppingcart, activity $activity, int $amount)
    {
        $this->shoppingcart = $shoppingcart;
        $this->activity = $activity;
        $this->amount = $amount;
        return $this;
    }

    public function getShoppingcart()
    {
        return $this->shoppingcart;
    }

    public function setShoppingcart($shoppingcart)
    {
        $this->shoppingcart = $shoppingcart;
    }

    public function getId()
    {
        return $this->activity->getId();
    }

    public function getActivity()
    {
        return $this->activity;
    }

    public function setActivity($activity)
    {
        $this->activity = $activity;
    }


    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function sqlGetFields()
    {
        return [
            "shoppingcartId" => $this->shoppingcart->getId(),
            "activityId" => $this->activity->getId(),
            "amount" => $this->getAmount()
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "shoppingcartId"],
            $sqlRes[self::sqlTableName . "activityId"],
            $sqlRes[self::sqlTableName . "amount"]
        );
    }
}