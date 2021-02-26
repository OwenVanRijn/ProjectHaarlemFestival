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

    // TODO: this is a lot of dupe code
    public const getHtmlEditHeader = [
        "activity" => [
            "activityId" => htmlTypeEnum::hidden,
            "type" => htmlTypeEnum::hidden,
            "date" => [htmlTypeEnum::date, account::accountScheduleManager],
            "startTime" => [htmlTypeEnum::time, account::accountScheduleManager],
            "endTime" => [htmlTypeEnum::time, account::accountScheduleManager],
            "price" => [htmlTypeEnum::number, account::accountTicketManager],
            "ticketsLeft" => [htmlTypeEnum::number, account::accountTicketManager]
            // TODO: implement location?
        ],
        "artists" => [ // TODO: This needs some custom type!
            "artistIds" => htmlTypeEnum::hidden,
        ]
    ];

    public function getHtmlEditFields(danceActivity $a): array
    {
        $artists = $a->getArtists();
        $artistIds = "";
        foreach ($artists as $b){
            $artistIds .= $b->getId();
        }

        return [
            "activityId" => $a->getActivity()->getId(),
            "type" => $a->getActivity()->getType(),
            "date" => $a->getActivity()->getDate()->format("d-m-Y"),
            "startTime" => $a->getActivity()->getStartTime()->format("H:i:s"),
            "endTime" => $a->getActivity()->getEndTime()->format("H:i:s"),
            "price" => $a->getActivity()->getPrice(),
            "ticketsLeft" => $a->getActivity()->getTicketsLeft(),
            "artistIds" => $artistIds
        ];
    }
}