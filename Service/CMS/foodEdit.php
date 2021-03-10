<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("editBase.php");
require_once ($root . "/Service/foodactivityService.php");
require_once ($root . "/Service/restaurantService.php");
require_once ($root . "/Service/restaurantTypeService.php");

class foodEdit extends editBase
{
    private restaurantService $restaurantService;
    private restaurantTypeService $restaurantTypeService;

    public function __construct()
    {
        parent::__construct(new foodactivityService());
        $this->restaurantService = new restaurantService();
        $this->restaurantTypeService = new restaurantTypeService();
    }

    public const editType = "Food";

    public const htmlEditHeader = [
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

    public function getHtmlEditFields(sqlModel $a) : array
    {
        $resTypeStrs = $this->restaurantTypeService->getAllTypesAsStr();
        $resCurTypeStrs = $this->restaurantTypeService->getRestaurantTypesAsIds($a->getRestaurant()->getId());

        $strs = $this->restaurantService->getAllRestaurantsAsStr();

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

    protected function processEditResponseChild(array $post){
        if (!isset($post["restaurantIncomplete"]) && isset($post["location"])){
            $restaurantId = -1;

            if (isset($post["restaurant"]) && (int)$post["restaurant"] == -1){
                $res = $this->restaurantService->insertRestaurant(
                    $post["name"],
                    $post["description"],
                    (int)$post["stars"],
                    (int)$post["seats"],
                    (int)$post["phoneNumber"],
                    (float)$post["restaurantPrice"],
                    (int)$post["location"]
                );

                if (!$res)
                    throw new appException("[Restaurant] db insert failed...");

                $post["restaurant"] = $res;
                $restaurantId = $res;
                $post["restaurantIncomplete"] = true;
            }
            else {
                $res = $this->restaurantService->updateRestaurant(
                    (int)$post["restaurantId"],
                    $post["name"],
                    $post["description"],
                    (int)$post["stars"],
                    (int)$post["seats"],
                    (int)$post["phoneNumber"],
                    (float)$post["restaurantPrice"],
                    (isset($post["locationIncomplete"])) ? (int)$post["location"] : null
                );

                $restaurantId = (int)$post["restaurantId"];

                if (!$res)
                    throw new appException("[Restaurant] db update failed...");
            }


            $this->restaurantTypeService->updateFieldIds($restaurantId, $post["restaurantType"]);
        }

        if (isset($post["restaurantIncomplete"])){
            $this->service->updateRestaurantId(
                (int)$post["foodActivityId"],
                (isset($post["restaurant"])) ? (int)$post["restaurant"] : (int)$post["restaurantId"]
            );
        }
    }
}