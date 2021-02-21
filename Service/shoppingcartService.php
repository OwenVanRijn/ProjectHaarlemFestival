<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Service/baseService.php");
require_once($root . "/Model/shoppingcart.php");
require_once($root . "/DAL/shoppingcartDAO.php");
require_once($root . "/DAL/activityDAO.php");

class shoppingcartService extends baseService
{
    
    private shoppingcart $shoppingcart;

    public function __construct()
    {
        $this->shoppingcart = new shoppingcart();
        $this->check();
    }



    public function getShoppingcart()
    {
        return $this->shoppingcart;
    }

    public function setShoppingcart($shoppingcart)
    {
        $this->shoppingcart = $shoppingcart;

        return $this;
    }


    public function check()
    {
        // DeleteAll
        if (isset($_POST["delete"])) {
            if ($_GET["operation"] == "delete") {
                $this->getShoppingcart()->removeFromShoppingcartItemsById($_GET["id"]);
            }
        }

        // Aftrekken
        if (isset($_POST["remove"])) {
            if ($_GET["operation"] == "remove") {
                $this->getShoppingcart()->addToShoppingcartItemsById($_GET["id"], -$_GET["amount"]);
            }
        }

        // Toevoegen
        if (isset($_POST["add"])) {
            if ($_GET["operation"] == "add") {
                $this->getShoppingcart()->addToShoppingcartItemsById($_GET["id"], $_GET["amount"]);
            }
        }

        if (isset($_POST["share"])) {
            if ($_GET["operation"] == "share") {
                $shoppingcartDB = new dynamicQueryGen(shoppingcart::class);
                $shoppingcartDB->insert($this->getShoppingcart()->sqlGetFields());
            }
        }
    }

    public function getInformationById($id)
    {
        $activityDAO = new activityDAO();
        return $activityDAO->getActivityInfo($id);
    }
}
