<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("activityBaseService.php");
require_once ($root . "/DAL/foodactivityDAO.php");
require_once ($root . "/Model/account.php");
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

    public const getHtmlEditHeader = [
        "restaurant" => [
            "restaurantId" => htmlTypeEnum::hidden,
            "name" => htmlTypeEnum::text,
            "description" => htmlTypeEnum::text,
            "stars" => htmlTypeEnum::number,
            "seats" => htmlTypeEnum::number,
            "phoneNumber" => htmlTypeEnum::number,
            "restaurantPrice" => [htmlTypeEnum::number, account::accountTicketManager],
            "restaurantType" => htmlTypeEnum::listMultiple
        ]
    ];

    public function getHtmlEditFields(foodactivity $a): array
    {
        $resTypeStrs = $this->types->getAllTypesAsStr();
        $resCurTypeStrs = $this->types->getRestaurantTypes($a->getId());

        return [
            "restaurantId" => $a->getRestaurant()->getId(),
            "name" => $a->getRestaurant()->getName(),
            "description" => $a->getRestaurant()->getDescription(),
            "stars" => $a->getRestaurant()->getStars(),
            "seats" => $a->getRestaurant()->getSeats(),
            "phoneNumber" => $a->getRestaurant()->getPhoneNumber(),
            "restaurantPrice" => $a->getRestaurant()->getPrice(),
            "restaurantType" => [
                "options" => $resTypeStrs,
                "selected" => $resCurTypeStrs
            ]
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
}