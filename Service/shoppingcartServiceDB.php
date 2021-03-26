<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Service/baseService.php");
require_once($root . "/Model/shoppingcartDB.php");
require_once($root . "/DAL/shoppingcartDAO.php");
require_once($root . "/DAL/shoppingcartItemDAO.php");
require_once($root . "/Service/shoppingcartService.php");
require_once($root . "/Service/activityService.php");
require_once($root . "/DAL/ordersDAO.php");

require_once($root . "/Service/jazzactivityService.php");
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/danceActivityService.php");

require_once($root . "/Model/activity.php");


class shoppingcartServiceDB extends baseService
{
    private shoppingcartDB $shoppingcart;
    private shoppingcartItemDAO $shoppingcartItemDAO;
    private shoppingcartService $shoppingcartService;
    private shoppingcartDAO $shoppingcartDAO;
    private activityService $activityService;

    public function __construct()
    {
        $this->shoppingcartDB = new shoppingcartDB();
        $this->shoppingcartItemDAO = new shoppingcartItemDAO();
        $this->shoppingcartService = new shoppingcartService();
        $this->shoppingcartDAO = new shoppingcartDAO();
        $this->activityService = new activityService();
    }


    public function getShoppingcart()
    {
        return $this->shoppingcartDAO->get();
    }

    public function addShoppingcartToDatabase()
    {
        $insert = [
            "url" => "",
            "createDate" => date("Y-m-d")
        ];

        $shoppingcartId = $this->shoppingcartDAO->insert($insert);

        $items = $this->shoppingcartService->getShoppingcart()->getShoppingcartItems();
        foreach ($items as $id => $amount) {
            $activity = $this->activityService->getById($id);
            $price = $activity->getPrice()*$amount;
            $insert = [
                "shoppingcartId" => $shoppingcartId,
                "activityId" => $activity->getId(),
                "amount" => intval($amount),
                "price" => $price
            ];

            var_dump($insert);

            $this->shoppingcartItemDAO->insert($insert);
        }
    }

    public function getShoppingcartById($id){
        return $this->shoppingcartItemDAO->get([
                "id" => $id
        ]);
    }


}