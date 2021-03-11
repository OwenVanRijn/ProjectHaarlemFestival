<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("activityBaseService.php");
require_once ($root . "/DAL/artistOnActivityDAO.php");
require_once ($root . "/DAL/danceActivityDAO.php");
require_once ($root . "/DAL/danceArtistDAO.php");
require_once("restaurantTypeLinkService.php");
require_once ("artistOnActivityService.php");
require_once ("danceArtistService.php");

class danceActivityService extends activityBaseService
{
    public function __construct()
    {
        $this->db = new artistOnActivityDAO();
    }

    public function getActivities(){
        return $this->getAll();
    }

    public function getTablesChild(account $a, array $cssRules, array $dates) : array
    {
        $tables = [];

        foreach ($dates as $k => $v){
            $table = new table();
            $table->setTitle($k);
            $table->setIsCollapsable(true);
            $table->addHeader("Time", "Name", "Location", "Type");
            foreach ($v as $c){
                $startDateStr = $c->getActivity()->getStartTime()->format("H:i");
                $endDateStr = $c->getActivity()->getEndTime()->format("H:i");

                $artists = "";

                foreach ($c->getArtists() as $artist){
                    $artists .= $artist->getName() . " & ";
                }

                $artists = substr($artists, 0, -3);

                $tableRow = new tableRow();
                $tableRow->addString(
                    "$startDateStr to $endDateStr",
                    $artists,
                    $c->getActivity()->getLocation()->getName(),
                    $c->getType()
                );

                $tableRow->addButton('openBox('. $c->getActivity()->getId() . ')', "Edit");

                $table->addTableRows($tableRow);
            }
            $table->assignCss($cssRules);
            $tables[] = $table;
        }

        return $tables;
    }

    private function toDanceActivityArray(array $aoaArray){
        $trackIds = [];

        foreach ($aoaArray as $aoa){
            if (!array_key_exists($aoa->getActivity()->getId(), $trackIds))
                $trackIds[$aoa->getActivity()->getId()] = $aoa->getActivity();

            $trackIds[$aoa->getActivity()->getId()]->addArtist($aoa->getArtist());
        }

        return array_values($trackIds);
    }

    public function getAll(): array
    {
        $res =  $this->db->get([
            "order" => ["activity.date", "activity.starttime", "activity.endtime"]
        ]);

        return $this->toDanceActivityArray($res);
    }

    public function getFromActivityIds(array $ids){
        return $this->toDanceActivityArray(parent::getFromActivityIds($ids));
    }

    // Format Y-m-d. Needs change
    public function getAllWithDate(string $date){
        $res =  $this->db->get([
            "activity.date" => $date,
            "order" => ["activity.date", "activity.starttime", "activity.endtime"]
        ]);

        return $this->toDanceActivityArray($res);
    }

    public function updateSessionType(int $id, string $sessionType){
        return (new danceActivityDAO())->update([
            "id" => $id,
            "sessionType" => $sessionType
        ]);
    }
}