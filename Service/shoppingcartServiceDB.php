<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Service/baseService.php");
require_once($root . "/Model/shoppingcartDB.php");
require_once($root . "/DAL/shoppingcartDAO.php");
require_once($root . "/DAL/shoppingcartItemDAO.php");
require_once($root . "/DAL/activityDAO.php");
require_once($root . "/DAL/ordersDAO.php");

require_once($root . "/Service/jazzactivityService.php");
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/danceActivityService.php");

require_once($root . "/Model/jazzactivity.php");
require_once($root . "/Model/foodactivity.php");
require_once($root . "/Model/danceActivity.php");


class shoppingcartServiceDB extends baseService
{
    private shoppingcartDB $shoppingcart;
    private shoppingcartItemDAO $shoppingcartItemDAO;
    private shoppingcartDAO $shoppingcartDAO;

    public function __construct()
    {
        $this->shoppingcartDB = new shoppingcartDB();
        $this->shoppingcartItemDAO = new shoppingcartItemDAO();
        $this->shoppingcartDAO = new shoppingcartDAO();
    }


    public function getShoppingcart()
    {
        return $this->shoppingcartDAO->get();
    }

    public function getShoppingcartItems()
    {
        return $this->shoppingcartItemDAO->get();
    }
}