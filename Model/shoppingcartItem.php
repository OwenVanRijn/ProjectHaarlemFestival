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
    /**
     * @return shoppingcart
     */
    public function getShoppingcart()
    {
        return $this->shoppingcart;
    }

    /**
     * @param shoppingcart $shoppingcart
     */
    public function setShoppingcart($shoppingcart)
    {
        $this->shoppingcart = $shoppingcart;
    }

    /**
     * @return activity
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param activity $activity
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;
    }

    /**
     * @return foodactivity
     */
    public function getFoodactivity()
    {
        return $this->foodactivity;
    }

    /**
     * @param foodactivity $foodactivity
     */
    public function setFoodactivity($foodactivity)
    {
        $this->foodactivity = $foodactivity;
    }

    /**
     * @return jazzactivity
     */
    public function getJazzactivity()
    {
        return $this->jazzactivity;
    }

    /**
     * @param jazzactivity $jazzactivity
     */
    public function setJazzactivity($jazzactivity)
    {
        $this->jazzactivity = $jazzactivity;
    }

    /**
     * @return danceactivity
     */
    public function getDanceactivity()
    {
        return $this->danceactivity;
    }

    /**
     * @param danceactivity $danceactivity
     */
    public function setDanceactivity($danceactivity)
    {
        $this->danceactivity = $danceactivity;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
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