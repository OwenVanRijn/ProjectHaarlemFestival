<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once("activityBaseService.php");
require_once($root . "/DAL/jazzactivityDAO.php");
require_once($root . "/DAL/jazzbandDAO.php");

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
            "bandDescription" => htmlTypeEnum::text,
        ],
        "performance" => [
            "hall" => htmlTypeEnum::text,
            "seats" => htmlTypeEnum::number
        ]
    ];

    public function getHtmlEditFields(jazzactivity $a){
        $bands = (new jazzbandDAO())->get();
        $bandsStr = [];
        foreach ($bands as $b){
            $bandsStr[] = $b->getName();
        }

        $selBand = $a->getJazzband();

        return [
            "band" => [
                "options" => $bandsStr,
                "selected" => $selBand->getName()
            ],
            "bandName" => $selBand->getName(),
            "bandDescription" => $selBand->getDescription(),
            "hall" => $a->getHall(),
            "seats" => $a->getSeats()
        ];
    }
}