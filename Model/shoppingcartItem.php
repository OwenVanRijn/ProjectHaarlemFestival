<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Model/sqlModel.php");
require_once($root . "/Model/shoppingcartDB.php");
require_once($root . "/Model/activity.php");

class shoppingcartItem extends sqlModel
{
    private int $id;
    private shoppingcartDB $shoppingcart;
    private activity $activity; //same as activityId
    private int $amount; //hoeveel items
    private float $price; //prijs per item

    protected const sqlTableName = "shoppingcartItem";
    protected const sqlFields = ["id", "shoppingcartId", "activityId", "amount", "price"];
    protected const sqlLinks = ["shoppingcartId" => shoppingcartDB::class, "activityId" => activity::class];

    public function constructFull(int $id, shoppingcartDB $shoppingcart, activity $activity, int $amount, float $price)
    {
        $this->id = $id;
        $this->shoppingcart = $shoppingcart;
        $this->activity = $activity;
        $this->amount = $amount;
        $this->price = $price;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getShoppingcart()
    {
        return $this->shoppingcart;
    }

    public function setShoppingcart($shoppingcart)
    {
        $this->shoppingcart = $shoppingcart;
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

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->getId(),
            "shoppingcartId" => $this->shoppingcart->getId(),
            "activityId" => $this->activity->getId(),
            "amount" => $this->getAmount(),
            "price" => $this->getAmount()
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            shoppingcartDB::sqlParse($sqlRes),
            activity::sqlParse($sqlRes),
            $sqlRes[self::sqlTableName . "amount"],
            $sqlRes[self::sqlTableName . "price"]
        );
    }
}