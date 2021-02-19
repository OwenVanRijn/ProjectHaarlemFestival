<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/tableInterface.php");
require_once ("baseService.php");
require_once ($root . "/DAL/jazzactivityDAO.php");

class jazzactivityService extends baseService implements tableInterface
{

    public function __construct()
    {
        $this->db = new jazzactivityDAO();
    }
    public function getContent(): array
    {
        $table = [];
        $table["header"] = ["Time", "Name", "Location"];

        $content = $this->db->get();
        if (is_null($content))
            return $table;

        if (gettype($content) != "array")
            $content = [$content];

        $dates = [];

        usort($content, function ($a, $b){
           return $a->getActivity()->getStartTime()->getTimestamp() - $b->getActivity()->getStartTime()->getTimestamp();
        });

        foreach ($content as $c){
            $dateStr = $c->getActivity()->getDate()->format("Y-m-d");

            if (!isset($dates[$dateStr])){
                $dates[$dateStr] = [];
            }

            $startDateStr = $c->getActivity()->getStartTime()->format("H:i");
            $endDateStr = $c->getActivity()->getEndTime()->format("H:i");
            $nameStr = $c->getJazzband()->getName();
            $locationStr = $c->getHall();

            $dates[$dateStr][] = [
                "$startDateStr to $endDateStr",
                $nameStr,
                $locationStr
            ];
        }

        uksort($dates, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });


        $table["sections"] = $dates;

        return $table;
    }
}