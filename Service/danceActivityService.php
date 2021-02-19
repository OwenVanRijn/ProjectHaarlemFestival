<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("activityBaseService.php");
require_once ($root . "/DAL/artistOnActivityDAO.php");
require_once ("restaurantTypeService.php");

class danceActivityService extends activityBaseService
{
    public function __construct()
    {
        $this->db = new artistOnActivityDAO();
    }

    public function getActivities(){
        return $this->getAll();
    }

    public function getFields(): array
    {
        return [
            "Name" => function (danceActivity $a){
                $artists = "";

                foreach ($a->getArtists() as $artist){
                    $artists .= $artist->getName() . " & ";
                }

                return substr($artists, 0, -3);
            },
            "Location" => function (danceActivity $a){
                return $a->getActivity()->getLocation()->getName();
            },
            "Type" => function (danceActivity $a){
                return $a->getType();
            }
        ];
    }

    public function getAll(): array
    {
        $res = $this->db->get();

        $trackIds = [];

        foreach ($res as $aoa){
            if (!array_key_exists($aoa->getActivity()->getId(), $trackIds))
                $trackIds[$aoa->getActivity()->getId()] = $aoa->getActivity();

            $trackIds[$aoa->getActivity()->getId()]->addArtist($aoa->getArtist());
        }

        return array_values($trackIds);
    }
}