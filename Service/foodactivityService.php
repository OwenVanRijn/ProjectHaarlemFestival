<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("activityBaseService.php");
require_once ($root . "/DAL/foodactivityDAO.php");
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

    // TODO: Add account as arg
    public function getHtmlDataById(int $id){

        $activityFilter = [];
        $restaurantFilter = [];

        // TODO: filter by account perms

        return $this->db->get(["id" => $id])->toHtmlArray($activityFilter, $restaurantFilter);
    }
}