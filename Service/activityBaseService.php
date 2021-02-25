<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/tableInterface.php");
require_once ($root . "/Service/baseService.php");

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
            $dateStr = $c->getActivity()->getDate()->format("Y-m-d");

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

    // TODO: add account later
    public function getHtmlEditContent(int $id): array
    {
        $entry = $this->getFromActivityIds([$id])[0];
        $header = static::getHtmlEditHeader;
        $fields = $this->getHtmlEditFields($entry);

        $res = [];
        foreach ($header as $hk => $hv){
            $classField = [];
            foreach ($hv as $k => $v){
                $classField[$k] = ["type" => $v, "value" => $fields[$k]];
            }
            $res[$hk] = $classField;
        }

        return $res;
    }
}