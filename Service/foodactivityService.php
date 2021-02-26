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
        "activity" => [
            "activityId" => htmlTypeEnum::hidden,
            "type" => htmlTypeEnum::hidden,
            "date" => [htmlTypeEnum::date, account::accountScheduleManager],
            "startTime" => [htmlTypeEnum::time, account::accountScheduleManager],
            "endTime" => [htmlTypeEnum::time, account::accountScheduleManager],
            "price" => [htmlTypeEnum::number, account::accountTicketManager],
            "ticketsLeft" => [htmlTypeEnum::number, account::accountTicketManager]
            // TODO: implement location?
        ],
        "restaurant" => [
            "restaurantId" => htmlTypeEnum::hidden,
            "name" => htmlTypeEnum::text,
            "description" => htmlTypeEnum::text,
            "stars" => htmlTypeEnum::number,
            "seats" => htmlTypeEnum::number,
            "phoneNumber" => htmlTypeEnum::number,
            "restaurantPrice" => [htmlTypeEnum::number, account::accountTicketManager],
            "restaurantType" => htmlTypeEnum::listMultiple
        ],
        "location" => [
            "locationId" => htmlTypeEnum::hidden,
            "locationName" => htmlTypeEnum::text,
            "address" => htmlTypeEnum::text,
            "postalCode" => htmlTypeEnum::text,
            "city" => htmlTypeEnum::text
        ]
    ];

    public function getHtmlEditFields(foodactivity $a): array
    {
        $restaurantTypes = new restaurantTypeService();

        $resTypeStrs = $restaurantTypes->getAllTypesAsStr();
        $resCurTypeStrs = $restaurantTypes->getRestaurantTypes($a->getId());

        return [
            "activityId" => $a->getActivity()->getId(),
            "type" => $a->getActivity()->getType(),
            "date" => $a->getActivity()->getDate()->format("d-m-Y"),
            "startTime" => $a->getActivity()->getStartTime()->format("H:i:s"),
            "endTime" => $a->getActivity()->getEndTime()->format("H:i:s"),
            "price" => $a->getActivity()->getPrice(),
            "ticketsLeft" => $a->getActivity()->getTicketsLeft(),
            "locationId" => $a->getActivity()->getLocation()->getId(),
            "locationName" => $a->getActivity()->getLocation()->getName(),
            "address" => $a->getActivity()->getLocation()->getAddress(),
            "postalCode" => $a->getActivity()->getLocation()->getPostalcode(),
            "city" => $a->getActivity()->getLocation()->getCity(),
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