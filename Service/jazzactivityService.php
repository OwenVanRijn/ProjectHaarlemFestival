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

    public function updateActivity(int $id, ?string $hall, ?int $seats, ?int $jazzBandId){
        $update = [
            "id" => $id,
        ];

        if (!is_null($hall))
            $update["hall"] = $hall;

        if (!is_null($seats))
            $update["seats"] = $seats;

        if (!is_null($jazzBandId))
            $update["jazzbandid"] = $jazzBandId;

        return $this->db->update($update);
    }
}