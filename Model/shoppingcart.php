<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/sqlModel.php");
require_once($root . "/Utils/cookieManager.php");


class shoppingcart extends sqlModel
{
    private int $id;
    private string $url;
    private DateTime $createDate;
    private array $shoppingcartItems;
    private cookieManager $cookieManager;

    protected const sqlTableName = "shoppingcart";
    protected const sqlFields = ["id", "url", "createDate"];


    public function __construct()
    {
        $this->id = 0;
        $this->url = "";
        $this->createDate = new DateTime();
        $this->shoppingcartItems = array();
        $this->cookieManager = new cookieManager("shoppingcart");
        //$this->addToShoppingcartItemsById(2, 5);
        //$this->addToShoppingcartItemsById(1, 6);
        //$this->addToShoppingcartItemsById(121, 2);

        return $this;
    }


    public function constructFull(int $id, string $url, DateTime $createDate)
    {
        $this->id = $id;
        $this->url = $url;
        $this->createDate = $createDate;
        $this->shoppingcartItems = array();
        $this->cookieManager = new cookieManager("shoppingcart");

        return $this;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "url" => $this->url,
            "createDate" => $this->createDate
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "url"],
            $sqlRes[self::sqlTableName . "createDate"]
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getShoppingcartItems()
    {
        $shoppingCart = $this->cookieManager->get();
        if (is_null($shoppingCart))
            return [];

        return unserialize($shoppingCart);
    }

    public function setShoppingcartItems(array $shoppingcart)
    {
        $this->cookieManager->set(serialize($shoppingcart), 0);
        $this->shoppingcartItems = $shoppingcart;
    }

    public function addToShoppingcartItemsById($shoppingcartItemId, $amount)
    {
        $shoppingcartItems = $this->getShoppingcartItems();
        $shoppingcartItems[$shoppingcartItemId] = $amount;
        $this->setShoppingcartItems($shoppingcartItems);

        return $this;
    }

    public function removeFromShoppingcartItemsById($shoppingcartItemId) //helemaal verwijderen
    {
        $shoppingcartItems = $this->getShoppingcartItems();
        unset($shoppingcartItems[$shoppingcartItemId]);
        $this->setShoppingcartItems($shoppingcartItems);
        return $this;
    }

    public function setShoppingcartItemById($shoppingcartItemId, $amount) //aanpassen
    {

        echo "set shopping cart id $shoppingcartItemId naar $amount";
        $shoppingcartItems = $this->getShoppingcartItems();
        $shoppingcartItems[$shoppingcartItemId] = $amount;
        $this->setShoppingcartItems($shoppingcartItems);
        return $this;
    }
}