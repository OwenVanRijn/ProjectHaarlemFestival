<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once("editActivityBase.php");
require_once ($root . "/Service/foodactivityService.php");
require_once ($root . "/Service/restaurantService.php");
require_once ($root . "/Service/restaurantTypeLinkService.php");

class foodEditActivity extends editActivityBase
{
    private restaurantService $restaurantService;
    private restaurantTypeLinkService $restaurantTypeService;

    public function __construct(account $account)
    {
        parent::__construct(new foodactivityService(), $account);
        $this->restaurantService = new restaurantService();
        $this->restaurantTypeService = new restaurantTypeLinkService();
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
            "restaurantPrice" => [htmlTypeEnum::float, account::accountTicketManager],
            "restaurantType" => htmlTypeEnum::listMultiple,
            "restaurantParking" => htmlTypeEnum::text,
            "restaurantWebsite" => htmlTypeEnum::text,
            "restaurantMenu" => htmlTypeEnum::text,
            "restaurantContact" => htmlTypeEnum::text,
            "image" => htmlTypeEnum::imgUpload,
        ],
        "hidden" => [
            "foodActivityId" => htmlTypeEnum::hidden
        ]
    ];

    public function getHtmlEditFieldsChild(sqlModel $a) : array
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
            "restaurantParking" => $a->getRestaurant()->getParking(),
            "restaurantWebsite" => $a->getRestaurant()->getWebsite(),
            "restaurantMenu" => $a->getRestaurant()->getMenu(),
            "restaurantContact" => $a->getRestaurant()->getContact(),
            "image" => "",
        ];
    }

    public function getHtmlEditFieldsEmpty() : array
    {
        $resTypeStrs = $this->restaurantTypeService->getAllTypesAsStr();
        $strs = $this->restaurantService->getAllRestaurantsAsStr();

        return [
            "restaurant" => [
                "options" => $strs,
                "selected" => -1
            ],
            "restaurantId" => "none",
            "name" => "",
            "description" => "",
            "stars" => "",
            "seats" => "",
            "phoneNumber" => "",
            "restaurantPrice" => "",
            "restaurantType" => [
                "options" => $resTypeStrs,
                "selected" => []
            ],
            "foodActivityId" => "none",
            "restaurantParking" => "",
            "restaurantWebsite" => "",
            "restaurantMenu" => "",
            "restaurantContact" => "",
            "image" => ""
        ];
    }

    protected function processEditResponseChild(array $post){
        if (!isset($post["restaurantIncomplete"]) && isset($post["location"])){
            if (isset($post["restaurant"]) && (int)$post["restaurant"] == -1){
                $res = $this->restaurantService->insertRestaurant(
                    $post["name"],
                    $post["description"],
                    (int)$post["stars"],
                    (int)$post["seats"],
                    (int)$post["phoneNumber"],
                    (isset($post["restaurantPrice"])) ? (float)$post["restaurantPrice"] : null,
                    (int)$post["location"],
                    $post["restaurantParking"],
                    $post["restaurantWebsite"],
                    $post["restaurantMenu"],
                    $post["restaurantContact"]
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
                    (isset($post["restaurantPrice"])) ? (float)$post["restaurantPrice"] : null,
                    (isset($post["locationIncomplete"])) ? (int)$post["location"] : null,
                    $post["restaurantParking"],
                    $post["restaurantWebsite"],
                    $post["restaurantMenu"],
                    $post["restaurantContact"]
                );

                $restaurantId = (int)$post["restaurantId"];

                if (!$res)
                    throw new appException("[Restaurant] db update failed...");
            }

            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $target_dir = $root . "/img/Restaurants";
            $target_file = $target_dir . "/restaurant" . $restaurantId . ".png";


            $this->restaurantTypeService->updateFieldIds($restaurantId, $post["restaurantType"]);
            $this->handleImage($target_file);
        }

        if (isset($post["restaurantIncomplete"])){
            $this->service->updateRestaurantId(
                (int)$post["foodActivityId"],
                (isset($post["restaurant"])) ? (int)$post["restaurant"] : (int)$post["restaurantId"]
            );
        }
    }

    protected function processNewResponseChild(array $post, int $activityId){

        if (!isset($post["restaurant"])) {
            throw new appException("Invalid POST");
        }

        if (isset($post["restaurant"]) && (int)$post["restaurant"] == -1){
            $res = $this->restaurantService->insertRestaurant(
                $post["name"],
                $post["description"],
                (int)$post["stars"],
                (int)$post["seats"],
                (int)$post["phoneNumber"],
                (isset($post["restaurantPrice"])) ? (float)$post["restaurantPrice"] : null,
                (int)$post["location"],
                $post["restaurantParking"],
                $post["restaurantWebsite"],
                $post["restaurantMenu"],
                $post["restaurantContact"]
            );

            if (!$res)
                throw new appException("[Restaurant] db insert failed...");

            $post["restaurant"] = $res;
            $post["restaurantIncomplete"] = true;
            $this->restaurantTypeService->updateFieldIds($post["restaurant"], $post["restaurantType"]);

            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $target_dir = $root . "/img/Restaurants";
            $target_file = $target_dir . "/restaurant" . (int)$post["restaurant"] . ".png";
            $this->handleImage($target_file);
        }

        $this->service->insertFoodActivity($activityId, (int)$post["restaurant"]);
    }
}