<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Model/sqlModel.php");
require_once($root . "/Model/shoppingcart.php");
require_once($root . "/Model/activity.php");
require_once($root . "/Model/foodactivity.php");
require_once($root . "/Model/jazzactivity.php");
require_once($root . "/Model/danceActivity.php");

class shoppingcartItem extends sqlModel
{
    private int $id; //same as activityId
    private shoppingcart $shoppingcart;
    private activity $activity;
    private foodactivity $foodactivity;
    private jazzactivity $jazzactivity;
    private danceactivity $danceactivity;
    private int $amount; //hoeveel items
    private float $price; //prijs

    protected const sqlTableName = "shoppingcartItem";
    protected const sqlFields = ["id", "shoppingcart", "activity"];
    protected const sqlLinks = ["shoppingcartId" => shoppingcart::class, "activityId" => activity::class];

    public function constructFull(int $id, shoppingcart $shoppingcart, activity $activity, foodactivity $foodactivity, jazzactivity $jazzactivity, danceactivity $danceactivity, int $amount, float $price)
    {
        echo "TESTING";

        $this->id = $id;
        $this->shoppingcart = $shoppingcart;
        $this->activity = $activity;
        $this->foodactivity = $foodactivity;
        $this->jazzactivity = $jazzactivity;
        $this->danceactivity = $danceactivity;
        $this->amount = $amount;
        $this->price = $price;
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