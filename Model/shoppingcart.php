<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Utils/cookieManager.php");


class shoppingcart
{
    private cookieManager $cookieManager;

    public function __construct()
    {
        $this->cookieManager = new cookieManager("shoppingcart");

        return $this;
    }


    public function constructFull()
    {
        $this->cookieManager = new cookieManager("shoppingcart");

        return $this;
    }

    public function getShoppingcartItems()
    {
        $shoppingCart = $this->cookieManager->get();
        if (is_null($shoppingCart))
            return [];

        return unserialize($shoppingCart);
    }


    public function getShoppingcartItemsCount()
    {
        $shoppingCart = $this->cookieManager->get();
        if (is_null($shoppingCart))
            return 0;

        $shoppingCart = unserialize($shoppingCart);
        return count($shoppingCart);
    }

    public function setShoppingcartItems(array $shoppingcart)
    {
        $this->cookieManager->set(serialize($shoppingcart), 0);
        $this->shoppingcartItems = $shoppingcart;
    }

    public function addToShoppingcartItemsById($shoppingcartItemId, $amount) //ACTIVITY ID , AMOUNT
    {
        $shoppingcartItems = $this->getShoppingcartItems();
        $shoppingcartItems[$shoppingcartItemId] += $amount;
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
        $shoppingcartItems = $this->getShoppingcartItems();
        $shoppingcartItems[$shoppingcartItemId] = $amount;
        $this->setShoppingcartItems($shoppingcartItems);
        return $this;
    }

    public function unsetShoppingcart() //aanpassen
    {
        $this->cookieManager->del();
        return true;
    }
}