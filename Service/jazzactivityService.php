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

    public function getTablesChild(account $a, array $cssRules, array $dates) : array
    {
        $tables = [];

        foreach ($dates as $k => $v){
            $table = new table();
            $table->setTitle($k);
            $table->setIsCollapsable(true);
            $table->addHeader("Time", "Name", "Location");
            $table->assignCss($cssRules);
            foreach ($v as $c){
                $startDateStr = $c->getActivity()->getStartTime()->format("H:i");
                $endDateStr = $c->getActivity()->getEndTime()->format("H:i");

                $tableRow = new tableRow();
                $table->addTableRows($tableRow);
                $tableRow->addString(
                    "$startDateStr to $endDateStr",
                    $c->getJazzband()->getName(),
                    $c->getActivity()->getLocation()->getAddress(),
                );

                $tableRow->addButton('openBox('. $c->getActivity()->getId() . ')', "Edit", "aid=\"". $c->getActivity()->getId() . "\"");
            }

            $tables[] = $table;
        }

        return $tables;
    }

    public function getAll(): array
    {
        return $this->db->get([
            "order" => ["activity.date", "activity.starttime", "activity.endtime"]
        ]);
    }
    public function getActivityFromId(int $id){
        return $this->db->get([
            "jazzactivity.id" => $id
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

    public function insertActivity(int $activityId, string $hall, int $seats, int $jazzBandId){
        $insert = [
            "jazzbandid" => $jazzBandId,
            "activityId" => $activityId,
            "hall" => $hall,
            "seats" => $seats
        ];

        return $this->db->insert($insert);
    }
}
