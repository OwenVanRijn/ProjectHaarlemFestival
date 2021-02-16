<?php

require_once ("sqlModel.php");
require_once ("jazzband.php");
require_once ("activity.php");

class jazzactivity extends sqlModel
{
    private int $id;
    private jazzband $jazzband;
    private activity $activity;
    private string $hall;
    private int $seats;


    protected const sqlTableName = "jazzactivity";
    protected const sqlFields = ["id", "jazzbandid", "activityId", "hall", "seats" ];
    protected const sqlLinks = ["jazzbandid" => jazzband::class, "activityId" => activity::class];

    public function __construct()
    {
        $this->id = -1;
        $this->jazzband = null;
        $this->activity = null;
        $this->hall = "unknown";
        $this->seats = 0;
    }

    public function constructFull(int $id, jazzband $jazzband, activity $activity, string $hall, int $seats)
    {
        $this->id = $id;
        $this->jazzband = $jazzband;
        $this->activity = $activity;
        $this->hall = $hall;
        $this->seats = $seats;
        return $this;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "jazzbandid" => $this->jazzband->getId(),
            "activityId" => $this->activity->getId(),
            "hall" => $this->hall,
            "seats" => $this->seats
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            jazzband::sqlParse($sqlRes),
            activity::sqlParse($sqlRes),
            $sqlRes[self::sqlTableName . "hall"],
            $sqlRes[self::sqlTableName . "seats"]
        );
    }


    public function getId()
    {
        return $this->id;
    }


    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getJazzband()
    {
        return $this->jazzband;
    }


    public function setJazzband($jazzband)
    {
        $this->jazzband = $jazzband;

        return $this;
    }

    public function getActivity()
    {
        return $this->activity;
    }

    
    public function setActivity($activity)
    {
        $this->activity = $activity;

        return $this;
    }

    public function getHall()
    {
        return $this->hall;
    }

    public function setHall($hall)
    {
        $this->hall = $hall;

        return $this;
    }

    public function getSeats()
    {
        return $this->seats;
    }

    public function setSeats($seats)
    {
        $this->seats = $seats;

        return $this;
    }
}