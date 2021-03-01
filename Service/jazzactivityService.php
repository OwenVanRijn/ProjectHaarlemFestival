<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once("activityBaseService.php");
require_once($root . "/DAL/jazzactivityDAO.php");
require_once($root . "/DAL/jazzbandDAO.php");
require_once ("jazzBandService.php");

class jazzactivityService extends activityBaseService
{
    public function __construct()
    {
        $this->db = new jazzactivityDAO();
    }

    public function getFields(): array
    {
        return [
            "Name" => function ($a) {
                return $a->getJazzband()->getName();
            },
            "Location" => function ($a) {
                return $a->getHall();
            }
        ];
    }

    public function getAll(): array
    {
        return $this->db->get([
            "order" => ["activity.date", "activity.starttime", "activity.endtime"]
        ]);
    }

    public const getHtmlEditHeader = [
        "band" => [
            "band" => htmlTypeEnum::list,
            "bandName" => htmlTypeEnum::text,
            "bandDescription" => htmlTypeEnum::textArea,
        ],
        "performance" => [
            "jazzActivityId" => htmlTypeEnum::hidden,
            "hall" => htmlTypeEnum::text,
            "seats" => htmlTypeEnum::number
        ]
    ];

    public function getHtmlEditFields(jazzactivity $a){
        $bands = (new jazzbandDAO())->get();
        $bandsStr = [];
        foreach ($bands as $b){
            $bandsStr[(string)$b->getId()] = $b->getName();
        }

        $selBand = $a->getJazzband();

        return [
            "band" => [
                "options" => $bandsStr,
                "selected" => $selBand->getId()
            ],
            "jazzActivityId" => $a->getId(),
            "bandName" => $selBand->getName(),
            "bandDescription" => $selBand->getDescription(),
            "hall" => $a->getHall(),
            "seats" => $a->getSeats()
        ];
    }

    public function postEditFields($post){
        if ($post["type"] != "Jazz" || isset($post["performanceIncomplete"]))
            return;

        $update = [
            "id" => $post["jazzActivityId"],
            "hall" => $post["hall"],
            "seats" => $post["seats"]
        ];

        if ($post["bandIncomplete"]){
            $update["jazzbandid"] = (int)$post["band"];
        }
        else {
            (new jazzBandService())->updateBand((int)$post["band"], $post["bandName"], $post["bandDescription"]);
        }

        if (!$this->db->update($update))
            throw new appException("Db update failed...");
    }
}