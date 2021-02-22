<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Service/baseService.php");
require_once($root . "/Model/shoppingcart.php");
require_once($root . "/DAL/shoppingcartDAO.php");
require_once($root . "/DAL/activityDAO.php");

require_once($root . "/Service/jazzactivityService.php");
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/danceActivityService.php");

require_once($root . "/Model/jazzactivity.php");
require_once($root . "/Model/foodactivity.php");
require_once($root . "/Model/danceActivity.php");


class shoppingcartService extends baseService
{

    private shoppingcart $shoppingcart;
    private activityDAO $activityDAO;
    private jazzactivityService $jazzactivityService;
    private foodactivityService $foodactivityService;
    private danceActivityService $danceActivityService;

    public function __construct()
    {
        $this->shoppingcart = new shoppingcart();
        $this->activityDAO = new activityDAO();
        $this->jazzactivityService = new jazzactivityService();
        $this->foodactivityService = new foodactivityService();
        $this->danceActivityService = new danceActivityService();
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


    public function getEventActivityInformationById($id)
    {
        $result = $this->activityDAO->getActivityInfo($id);
        var_dump($result);
        echo "<br><br>";

        $eventResult = array();
        echo $result[0]->getType() ;
        if ($result[0]->getType() == "food") {
            $eventResult[] = $this->foodactivityService->getFromActivityIds(["$id"]);
        } else if ($result[0]->getType() == "dance") {
            $eventResult[] = $this->danceActivityService->getFromActivityIds(["$id"]);
        } else if ($result[0]->getType() == "jazz") {
            $eventResult[] = $this->jazzactivityService->getFromActivityIds(["$id"]);
        }
        return array_merge($result, $eventResult);
        // return $this->activityDAO->getActivityInfo($id);
    }
}
