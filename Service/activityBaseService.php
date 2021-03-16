<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/tableInterface.php");
require_once($root . "/Model/account.php");
require_once ($root . "/Service/baseService.php");
require_once ($root . "/Utils/appException.php");
require_once ($root . "/DAL/locationDAO.php");

abstract class activityBaseService extends baseService
{
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

    public abstract function getTablesChild(account $a, array $cssRules, array $dates) : array;

    public function getTables(account $a, array $cssRules){
        $content = $this->getAll();

        if (is_null($content))
            return [];

        if (gettype($content) != "array")
            $content = [$content];

        $dates = [];

        foreach ($content as $c){
            $curDate = $c->getActivity()->getDate()->format("l (Y-m-d)");
            $dates[$curDate][] = $c;
        }

        return $this->getTablesChild($a, $cssRules, $dates);
    }

    public function deleteTypedActivity(array $activityIds){
        return $this->db->delete([
            "activityId" => $activityIds
        ]);
    }
}