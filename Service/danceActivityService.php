<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("activityBaseService.php");
require_once ($root . "/DAL/artistOnActivityDAO.php");
require_once ($root . "/DAL/danceActivityDAO.php");
require_once ($root . "/DAL/danceArtistDAO.php");
require_once ("restaurantTypeService.php");
require_once ("artistOnActivityService.php");

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
    
    public const getHtmlEditHeader = [
        "artists" => [ // TODO: This needs some custom type!
            "artistActivityId" => htmlTypeEnum::hidden,
            "eventType" => htmlTypeEnum::text,
            "artistsOnActivity" => htmlTypeEnum::listMultiple
        ]
    ];

    public function getHtmlEditFields(danceActivity $a): array
    {
        $artists = $a->getArtists();
        $artistSelStrs = [];
        foreach ($artists as $b){
            $artistSelStrs[] = $b->getId();
        }
        // TODO: Split off in different file!
        $allArtists = (new danceArtistDAO())->get();
        $artistStrs = [];
        foreach ($allArtists as $b){
            $artistStrs[(string)$b->getId()] = $b->getName();
        }

        return [
            "artistActivityId" => $a->getId(),
            "eventType" => $a->getType(),
            "artistsOnActivity" => [
                "options" => $artistStrs,
                "selected" => $artistSelStrs
            ]
        ];
    }

    public function postEditFields($post){
        if (isset($post["artistsIncomplete"]) || $post["type"] != "Dance")
            return;

        $update = [
            "id" => (int)$post["artistActivityId"],
            "sessionType" => $post["eventType"]
        ];

        (new artistOnActivityService())->updateArtistIds($update["id"], $post["artistsOnActivity"]);

        if (!(new danceActivityDAO())->update($update))
            throw new appException("Db update failed...");
    }
}