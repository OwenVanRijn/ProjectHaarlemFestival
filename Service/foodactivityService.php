<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("activityBaseService.php");
require_once ($root . "/DAL/foodactivityDAO.php");
require_once ($root . "/Model/account.php");
require_once ($root . "/DAL/dbContains.php");
require_once("restaurantTypeLinkService.php");

class foodactivityService extends activityBaseService
{
    private restaurantTypeLinkService $types;

    public function __construct(){
        $this->db = new foodactivityDAO();
        $this->types = new restaurantTypeLinkService();
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


    public function getBySessionDate(string $date, array $times, int $restaurantId){
        return $this->db->get([
            "activity.date" => $date,
            "activity.startTime" => "$times[0]",
            "activity.endTime" => "$times[1]",
            "restaurant.id" => $restaurantId
        ]);
    }

    public function updateRestaurantId(int $id, int $restaurantId){
        return $this->db->update([
            "id" => $id,
            "restaurantId" => $restaurantId // TODO: check for validity
        ]);
    }
}