<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("activityBaseService.php");
require_once ($root . "/DAL/foodactivityDAO.php");
require_once ($root . "/Model/account.php");
require_once ($root . "/DAL/dbContains.php");
require_once ("restaurantTypeService.php");

class foodactivityService extends activityBaseService
{
    private restaurantTypeService $types;

    public function __construct(){
        $this->db = new foodactivityDAO();
        $this->types = new restaurantTypeService();
    }

    public function getFields(): array
    {
        return [
            "Name" => function ($a){
                return $a->getRestaurant()->getName();
            },
            "Location" => function ($a){
                return $a->getActivity()->getLocation()->getAddress();
            },
            "Type" => function ($a){
                $types = $this->types->getRestaurantTypes($a->getRestaurant()->getId());
                return join("/", $types);
            }
        ];
    }

    public function getAll(): array
    {
        return $this->db->get([
            "order" => ["activity.date", "activity.starttime", "activity.endtime"]
        ]);
    }

    public function getFiltered(string $restaurantName, string $restaurantType, int $minStars){

    }

    public function getByRestaurantId(int $restaurantId){
        return $this->db->get([
            "restaurant.id" => new dbContains("$restaurantId")
        ]);
    }


    public function getBySessionDate(string $date, string $startendTime, int $restaurantId){
        $times = explode("-", $startendTime);
        return $this->db->get([
            "activity.date" => new dbContains("$date"),
            "activity.startTime" => new dbContains("$times[0]"),
            "activity.endTime" => new dbContains("$times[1]"),
            "restaurant.id" => new dbContains("$restaurantId")
        ]);
    }

    public function updateRestaurantId(int $id, int $restaurantId){
        return $this->db->update([
            "id" => $id,
            "restaurantId" => $restaurantId // TODO: check for validity
        ]);
    }
}