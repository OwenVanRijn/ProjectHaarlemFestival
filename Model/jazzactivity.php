<?php

require_once ("sqlModel.php");

class jazzactivity extends sqlModel
{
    private int $id;
    private int $jazzbandid;
    private int $activityId;
    private string $hall;
    private int $seats;


    protected const sqlTableName = "jazzactivity";
    protected const sqlFields = ["id", "jazzbandid", "activityId", "hall", "seats" ];

    public function __construct()
    {
        $this->id = -1;
        $this->jazzbandid = -1;
        $this->activityId = -1;
        $this->hall = "unknown";
        $this->seats = 0;
    }

    public function constructFull(int $id, int $jazzbandid, int $activityId, string $hall, int $seats)
    {
        $this->id = $id;
        $this->jazzbandid = $jazzbandid;
        $this->activityId = $activityId;
        $this->hall = $hall;
        $this->seats = $seats;
        return $this;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "jazzbandid" => $this->jazzbandid,
            "activityId" => $this->activityId,
            "hall" => $this->hall,
            "seats" => $this->seats
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "jazzbandid"],
            $sqlRes[self::sqlTableName . "activityId"],
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

    public function getJazzbandid()
    {
        return $this->jazzbandid;
    }


    public function setJazzbandid($jazzbandid)
    {
        $this->jazzbandid = $jazzbandid;

        return $this;
    }

    public function getActivityId()
    {
        return $this->activityId;
    }

    
    public function setActivityId($activityId)
    {
        $this->activityId = $activityId;

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