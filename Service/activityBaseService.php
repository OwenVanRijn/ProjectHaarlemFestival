<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/tableInterface.php");
require_once($root . "/Model/account.php");
require_once ($root . "/Service/baseService.php");
require_once ($root . "/Utils/appException.php");
require_once ($root . "/DAL/locationDAO.php");

abstract class activityBaseService extends baseService implements tableInterface
{
    public abstract function getFields() : array;

    public abstract function getAll() : array;

    public function getFromActivityIds(array $ids){
        $ret = $this->db->get([
            "activity.id" => $ids,
            "order" => ["activity.date", "activity.starttime", "activity.endtime"]
        ]);

        if (is_null($ret))
            return [];

        if (gettype($ret) != "array")
            return [$ret];

        return $ret;
    }

    public function getTableContent(): array
    {
        $table = [];
        $table["header"] = ["Time"];

        foreach ($this->getFields() as $f => $_) {
            $table["header"][] = $f;
        }

        $content = $this->getAll();
        if (is_null($content))
            return $table;

        if (gettype($content) != "array")
            $content = [$content];

        $dates = [];

        foreach ($content as $c){
            $dateStr = $c->getActivity()->getDate()->format("l") . " (" . $c->getActivity()->getDate()->format("Y-m-d") . ")";

            if (!isset($dates[$dateStr])){
                $dates[$dateStr] = [];
            }

            $startDateStr = $c->getActivity()->getStartTime()->format("H:i");
            $endDateStr = $c->getActivity()->getEndTime()->format("H:i");

            $local = [
                "$startDateStr to $endDateStr"
            ];

            foreach ($this->getFields() as $f) {
                $local[] = $f($c);
            }

            $local[] = "<button onclick='openBox(". $c->getActivity()->getId() .")'>Edit</button>";

            $dates[$dateStr][] = $local;
        }

        $table["sections"] = $dates;

        return $table;
    }


    /*
     * Template:
     * [
     *      class: [
     *          field: [
     *              type: str
     *              value: T
     * ]]]
     */

    //public abstract function getHtmlEditFields($entry) : array;

    public function filterHtmlEditResponse(account $account, array $postResonse){
        $header = array_merge(static::getHtmlEditHeader, self::getHtmlBaseEditHeader);
        $correctedPostResponse = [];

        foreach ($header as $hk => $hv){
            foreach ($hv as $k => $v){
                if (gettype($v) == "array"){
                    if (($account->getCombinedRole() & $v[1]))
                        if (array_key_exists($k, $postResonse))
                            $correctedPostResponse[$k] = htmlSpecialchars($postResonse[$k], ENT_QUOTES);
                        else
                            $correctedPostResponse[$hk . "Incomplete"] = false;
                }
                elseif (array_key_exists($k, $postResonse))
                    $correctedPostResponse[$k] = htmlSpecialchars($postResonse[$k], ENT_QUOTES);
                else
                    $correctedPostResponse[$hk . "Incomplete"] = true;
            }
        }

        return $correctedPostResponse;
    }

    public function getHtmlEditContent(int $id, account $account): array
    {
        $entries = $this->getFromActivityIds([$id]);
        if ($entries === [])
            throw new appException("Id not found");

        $entry = $entries[0];
        $header = array_merge(static::getHtmlEditHeader, self::getHtmlBaseEditHeader);
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

    public const getHtmlBaseEditHeader = [
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
            ]
        ];
    }
}