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

    public const getHtmlEditHeader = [
        "restaurant" => [
            "restaurant" => [htmlTypeEnum::list, account::accountTicketManager],
            "name" => htmlTypeEnum::text,
            "restaurantId" => htmlTypeEnum::hidden,
            "description" => htmlTypeEnum::textArea,
            "stars" => htmlTypeEnum::number,
            "seats" => htmlTypeEnum::number,
            "phoneNumber" => htmlTypeEnum::text,
            "restaurantPrice" => [htmlTypeEnum::number, account::accountTicketManager],
            "restaurantType" => htmlTypeEnum::listMultiple
        ],
        "hidden" => [
            "foodActivityId" => htmlTypeEnum::hidden
        ]
    ];

    public function getHtmlEditFields(foodactivity $a): array
    {
        $resTypeStrs = $this->types->getAllTypesAsStr();
        $resCurTypeStrs = $this->types->getRestaurantTypesAsIds($a->getRestaurant()->getId());

        $rest = new restaurantService();
        $strs = $rest->getAllRestaurantsAsStr();

        return [
            "restaurant" => [
                "options" => $strs,
                "selected" => $a->getRestaurant()->getId()
            ],
            "restaurantId" => $a->getRestaurant()->getId(), // Restaurant may not always be present!
            "name" => $a->getRestaurant()->getName(),
            "description" => $a->getRestaurant()->getDescription(),
            "stars" => $a->getRestaurant()->getStars(),
            "seats" => $a->getRestaurant()->getSeats(),
            "phoneNumber" => $a->getRestaurant()->getPhoneNumber(),
            "restaurantPrice" => $a->getRestaurant()->getPrice(),
            "restaurantType" => [
                "options" => $resTypeStrs,
                "selected" => $resCurTypeStrs
            ],
            "foodActivityId" => $a->getId(),
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

    public function postEditFields($post){
        if ($post["type"] != "Food")
            return;

        if (isset($post["restaurantIncomplete"])){
            $this->db->update([
                "id" => (int)$post["foodActivityId"],
                "restaurantId" => (int)$post["restaurant"] // TODO: check for validity
            ]);
        }
        else {
            $restaurant = new restaurantService();
            $restaurant->postEditFields($post);

            if (isset($post["restaurantUpdated"])){
                $this->db->update([
                    "id" => (int)$post["foodActivityId"],
                    "restaurantId" => (int)$post["restaurant"] // TODO: check for validity
                ]);
            }
        }
    }
}