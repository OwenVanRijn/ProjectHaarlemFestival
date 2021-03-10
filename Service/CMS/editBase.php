<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/tableInterface.php");
require_once($root . "/Model/account.php");
require_once ($root . "/Service/baseService.php");
require_once ($root . "/Utils/appException.php");
require_once ($root . "/DAL/locationDAO.php");
require_once ("editInterface.php");

abstract class editBase implements editInterface
{
    private activityBaseService $service;

    public function __construct(activityBaseService $service){
        $this->service = $service;
    }

    public const htmlBaseEditHeader = [
        "activity" => [
            "activityId" => htmlTypeEnum::hidden,
            "type" => htmlTypeEnum::hidden,
            "date" => [htmlTypeEnum::date, account::accountScheduleManager],
            "startTime" => [htmlTypeEnum::time, account::accountScheduleManager],
            "endTime" => [htmlTypeEnum::time, account::accountScheduleManager],
            "price" => [htmlTypeEnum::number, account::accountTicketManager],
            "ticketsLeft" => [htmlTypeEnum::number, account::accountTicketManager]
        ],
        "location" => [
            "locationName" => htmlTypeEnum::text,
            "location" => htmlTypeEnum::list, //we need a way to GET the location's details
            "address" => htmlTypeEnum::text,
            "postalCode" => htmlTypeEnum::text,
            "city" => htmlTypeEnum::text
        ]
    ];

    public function getHtmlBaseEditFields($a): array
    {
        $locationDAO = new locationDAO();
        $locations = $locationDAO->get();
        $locationStrings = [];
        foreach ($locations as $l){
            $locationStrings[(string)$l->getId()] = $l->getName();
        }

        return [
            "activityId" => $a->getActivity()->getId(),
            "type" => $a->getActivity()->getType(),
            "date" => $a->getActivity()->getDate()->format("Y-m-d"),
            "startTime" => $a->getActivity()->getStartTime()->format("H:i:s"),
            "endTime" => $a->getActivity()->getEndTime()->format("H:i:s"),
            "price" => $a->getActivity()->getPrice(),
            "ticketsLeft" => $a->getActivity()->getTicketsLeft(),
            "address" => $a->getActivity()->getLocation()->getAddress(),
            "postalCode" => $a->getActivity()->getLocation()->getPostalcode(),
            "city" => $a->getActivity()->getLocation()->getCity(),
            "location" => [
                "options" => $locationStrings,
                "selected" => $a->getActivity()->getLocation()->getId()
            ],
            "locationName" => $a->getActivity()->getLocation()->getName()
        ];
    }

    private function stripHtmlChars($input){
        switch (gettype($input)){
            case "string":
                if (empty($input))
                    throw new appException("Empty string provided!");

                return trim(htmlspecialchars($input, ENT_QUOTES));
            case "array":
                $new = [];
                foreach ($input as $a){
                    $new[] = $this->stripHtmlChars($a);
                }
                return $new;
            default:
                throw new appException("Can't strip type " . gettype($input));
        }
    }

    public function filterHtmlEditResponse(account $account, array $postResonse){
        $header = array_merge(static::htmlEditHeader, self::htmlBaseEditHeader);
        $correctedPostResponse = [];

        foreach ($header as $hk => $hv){
            foreach ($hv as $k => $v){
                if (gettype($v) == "array"){
                    if (($account->getCombinedRole() & $v[1]))
                        if (array_key_exists($k, $postResonse))
                            $correctedPostResponse[$k] = $this->stripHtmlChars($postResonse[$k]);
                        else
                            $correctedPostResponse[$hk . "Incomplete"] = true;
                }
                elseif (array_key_exists($k, $postResonse))
                    $correctedPostResponse[$k] = $this->stripHtmlChars($postResonse[$k]);
                else
                    $correctedPostResponse[$hk . "Incomplete"] = true;
            }
        }

        return $correctedPostResponse;
    }

    public function getHtmlEditContent(int $id, account $account): array
    {
        $entries = $this->service->getFromActivityIds([$id]);
        if ($entries === [])
            throw new appException("Id not found");

        $entry = $entries[0];
        $header = array_merge(static::htmlEditHeader, self::htmlBaseEditHeader);
        $fields = array_merge($this->getHtmlEditFields($entry), $this->getHtmlBaseEditFields($entry));

        $res = [];
        foreach ($header as $hk => $hv){
            $classField = [];
            foreach ($hv as $k => $v){
                if (gettype($v) == "array"){
                    if (($account->getCombinedRole() & $v[1]))
                        $classField[$k] = ["type" => $v[0], "value" => $fields[$k]];
                }
                else
                    $classField[$k] = ["type" => $v, "value" => $fields[$k]];
            }
            $res[$hk] = $classField;
        }

        return $res;
    }

    protected function processBaseEditResponse(array $post, account $account) {

    }

    public const htmlEditHeader = [];

    public abstract function getHtmlEditFields(sqlModel $a) : array;
    public abstract function processEditResponse(array $post, account $account);
}