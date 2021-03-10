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

    protected function processEditResponseChild(array $verifiedPost){

    }
}